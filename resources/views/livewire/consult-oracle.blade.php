<div class="flex flex-col xl:flex-row justify-between gap-6">
    <style>
        .chat-area {
            overflow: hidden;
            overflow-y: scroll;
        }
        .chat-area::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="w-full flex flex-col justify-between fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 mt-0 md:mt-1">
        <div class="h-96 chat-area p-3" id="chatBox">
            
            @if($greetingMsg == '')
                <div>
                    @foreach (Session::get('conversation_history', []) as $msg)
                        <div class="w-full flex justify-end mt-3 flex-col items-end">
                            <div class="flex flex-col justify-between fi-section rounded-xl bg-dark shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-3 mt- md:mt-1" style="background-color: rgb(23, 155, 239); max-width: 90%;">
                                <p>{{$msg['question']}}</p>
                            </div>
                            <p class="text-sm mt-1">{{$msg['hour']}}</p>
                        </div>
                            
                        <div class="w-full flex items-start">
                            <div class="">
                                <img class="w-10 h-10" src="{{url('storage/panel-assets/oracle_bot.png')}}" alt="Oráculo">
                            </div>
                            
                            <div style="max-width: 90%;">
                                <div class="flex flex-col justify-between fi-section rounded-xl bg-dark shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-3 mt-0 md:mt-1 mt-3" style="max-width: 90%;">
                                    <p>{!! $msg['answer'] !!}</p>
                                </div>
                                <p class="flex justify-end text-sm mt-1" style="max-width: 90%;">{{$msg['hour']}}</p>
                            </div>
                        </div>  
                    @endforeach     
                </div>
            @else
                <div class="w-full flex items-start">
                    <div class="">
                        <img class="w-10 h-10" src="{{url('storage/panel-assets/oracle_bot.png')}}" alt="Oráculo">
                    </div>
                    
                    <div style="max-width: 90%;">
                        <div class="flex flex-col justify-between fi-section rounded-xl bg-dark shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-3 mt-0 md:mt-1 mt-3">
                            <p>{!! $greetingMsg !!}</p>
                        </div>
                    </div>
                </div>  
            @endif
        </div>

        <select class="border-none bg-white shadow-sm bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 rounded-md w-full ps-3 pe-3" wire:change="changeModel" style="margin-top: 20px;">
            <option value="offline">Offline</option>
            <option value="online">Online</option>
        </select>

        <div class="flex">
            <input class="border-none bg-white shadow-sm bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 rounded-md w-full ps-3 pe-3 mt-3" type="text" name="prompt" wire:model="prompt" placeholder="Faça uma pergunta..." wire:keydown.enter='consultOracle'>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 cursor-pointer" wire:click="consultOracle" wire:loading.remove style="margin-left: -34px; margin-top:10px; color:rgb(23, 155, 239)">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7" wire:loading.delay style="margin-left: -34px; margin-top:10px; color:rgb(23, 155, 239)">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
              </svg>
        </div>
    </div>

    <div class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 w-72">
        <h3 class="w-full text-center">Sugestões</h3>

        @foreach ($repository as $key => $question)
            @if($key <= 10)
                <div class="flex flex-col my-2 cursor-pointer p-1 rounded bg-black w-72">
                    <p class="text-sm truncate-sm ..." wire:click="choseQuestion($event.target.innerText)">{{$question->question}}</p>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        scrollToBottom();
    });

    // Hooks Livewire para garantir que o componente foi montado e atualizado
    document.addEventListener('livewire:init', () => {
        Livewire.on('storeSessionInteraction', (event) => {
            scrollToBottom();
        });

        Livewire.on('choseQuestion', (event) => {
            scrollToBottom();
        });

        Livewire.on('minPromtLenght', (event) => {
            alert('Digite no mínimo 15 caracteres');
        });

        
    });

    function scrollToBottom() {
        var chatBox = document.getElementById('chatBox');
        if (chatBox) {
            // Aguarda 100ms para garantir que todo o conteúdo seja carregado antes de rolar
            setTimeout(function() {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }
    }
</script>
