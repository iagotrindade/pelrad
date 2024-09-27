<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Oracle;



class ConsultOracle extends Component
{
    public $prompt = '';
    public $result = '';
    public $repositoryPath;
    public $repository;

    public function render()
    {
        $this->repository = Oracle::all();

        return view('livewire.consult-oracle');
    }

    public function consultOracle() {
        if(strlen($this->prompt) <= 20 ) {
            $this->result = 'Pesquisando...';
        }
        
        $this->validate([
            'prompt' => ['required', 'min:15']
        ]);

        $closest = null;
        $highestSimilarity = 0;

        // Iterar sobre todas as perguntas extraídas do documento
        foreach ($this->repository as $item) {
            // Comparar a similaridade entre a pergunta do usuário e a pergunta no documento
            similar_text($this->prompt, $item->question, $similarity);

            // Se a similaridade atual for maior do que a maior similaridade encontrada
            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $closest = $item->answer; // Armazenar a pergunta mais similar
            }
        }
        sleep(1);
        $this->result = $closest;
    }

    public function choseQuestion($question) {
        $this->prompt = $question;

        $this->consultOracle();
    }
}
