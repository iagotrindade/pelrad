<?php

namespace App\Livewire;

use Log;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Oracle;
use GuzzleHttp\Client;
use Livewire\Component;
use App\Models\Material;
use App\Models\Compliance;
use App\Models\Maintenance;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use GuzzleHttp\Exception\RequestException;

class ConsultOracle extends Component
{
    public $iaData = [];
    public $model = 'offline';
    public $prompt = '';
    public $greetingMsg;
    public $result;
    public $repository;

    public function render()
    {
        $this->greetingMsg = Session::has('conversation_history') 
            ? '' 
            : 'Olá! Me faça uma pergunta.';
            
        $this->repository = Oracle::all();

        return view('livewire.consult-oracle');
    }

    public function changeModel() {
        $this->model = ($this->model == 'offline') ? 'online' : 'offline';
    }

    public function consultOracle() {
        if(strlen($this->prompt <= 15)) {
            $this->dispatch('minPromtLenght');
            return; // Termina a função até o prompt estar completo
        }

        // Realiza a consulta dependendo do estado do model
        $method = ($this->model == 'offline') ? 'offlineConsult' : 'onlineConsult';
        $this->$method();
     }

    public function offlineConsult() {
        $closest = null;
        $highestSimilarity = 50; // Define uma similaridade mínima
    
        // Iterar sobre todas as perguntas extraídas do documento
        foreach ($this->repository as $item) {
            // Comparar a similaridade entre a pergunta do usuário e a pergunta no documento
            similar_text($this->prompt, $item->question, $similarity);
    
            // Se a similaridade atual for maior do que a maior similaridade encontrada
            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $closest = $item->answer; // Armazenar a resposta da pergunta mais similar
            }
        }
    
        // Se não encontrar uma pergunta similar suficiente
        if ($closest === null) {
            $closest = 'Infelizmente não consegui entender sua pergunta. Por favor, tente digitar de outra forma ou use o modo online.';
        }

        $this->result = $closest;

        $this->storeSessionInteraction($this->prompt, $this->result);
    }

    public function onlineConsult() {
        $this->iaData = [
            'urlDaAplicação' => 'https://pelrad.app/',
            'atividades' => Activity::select('description', 'event', 'created_at')->get(),
            'materiais' => Material::select('name', 'description', 'status', 'categories_id')->with(['type:id,name'])->get(),
            'usuarios' => User::select('graduation', 'name', 'email')->get(),
            'cautelas' => Loan::select('to', 'graduation', 'name', 'contact', 'status', 'materials_info', 'return_date')->get(),
            'manutencoes' => Maintenance::select('status', 'destiny', 'created_at', 'file')->get(),
            'prontos' => Compliance::select('name', 'created_at')->get()
        ];
        
        $this->result = 'Pesquisando...';
        // Retrieve conversation history from session
        $history = $this->getSessionHistory();
        
        // Formatar o contexto com os dados para enviar para o Cohere
        
        foreach ($this->iaData['cautelas'] as $key => $loan) {
            $materialInfo = json_decode($loan['materials_info'], true);

            $this->iaData['cautelas'][$key]['materials_info'] = [
                'name' => $materialInfo[0]['name'],
            ];
        }

        $context = "Histórico de Conversa:\n" . $history . " Dados do sistema: " . json_encode($this->iaData);

        // Tratamento de exceções usando try-catch
    try {
        $client = new Client();
        $response = $client->post('https://api.cohere.com/v1/chat', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('COHERE_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'message' => $context . "\nPergunta: " . $this->prompt . "\nResposta:",
                'model' => 'command-r-08-2024',
                'preamble' => 'Seu nome é "Antigão" e foi desenvolvido pelo 3º Sgt Iago Silva. Você possuí conhecimento sobre todas informações do sistema. Sempre que não souber a respsota deve pesquisar o que não encontrar na internet. Você é treinado para ajudar os usuários, fornecendo respostas completas e úteis às suas dúvidas. Lembre-se que suas respostas devem ser sempre completas e o mais detalhadas possíveis'
            ]
        ]);

        // Processa a resposta da API Cohere
        $body = json_decode($response->getBody(), true);
        $this->result = $body['text'];

        } catch (RequestException $e) {
            // Captura a exceção de requisição
            $this->result = 'Erro ao consultar o Antigão. Por favor, tente novamente mais tarde ou use o modo offline.';

        } catch (\Exception $e) {
            // Captura outras exceções gerais
            $this->result = 'Ocorreu um erro inesperado. Tente novamente mais tarde ou use o modo offline.';
        }

        // Armazena a nova interação na sessão
        $this->storeSessionInteraction($this->prompt, $this->result);
    }

    // Function to retrieve conversation history from session
    public function getSessionHistory()
    {
        // Get history from session (or an empty array if none exists)
        $conversations = Session::get('conversation_history', []);

        $history = "";
        foreach ($conversations as $conversation) {
            $history .= "User: " . $conversation['question'] . "\n";
            $history .= "Oracle: " . $conversation['answer'] . "\n";
        }

        return $history;
    }

    public function choseQuestion($question) {
        $this->prompt = $question;

        $this->dispatch('choseQuestion');

        $this->offlineConsult();
    }

    // Function to store new interaction in session
    public function storeSessionInteraction($question, $answer)
    {
        // Get current conversation history
        $conversations = Session::get('conversation_history', []);

        // Add new question and answer to the history
        $conversations[] = [
            'question' => $question,
            'answer' => $answer,
            'hour' => Carbon::now()->format('H:i')
        ];

        // Keep only the last 5 interactions
        if (count($conversations) > 10) {
            array_shift($conversations); // Remove the oldest interaction
        }

        // Save the updated history to session
        Session::put('conversation_history', $conversations);

        $this->dispatch('storeSessionInteraction');
        $this->prompt = '';
    }

    // Function to reset the conversation history
    public function resetConversation()
    {
        Session::forget('conversation_history');
    }
}
