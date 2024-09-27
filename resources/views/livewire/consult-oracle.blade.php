<div class="flex flex-col xl:flex-row justify-between gap-6">
    <div class="w-full flex flex-col justify-between fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 mt-0 md:mt-1" >
        <div class="h-96 flex flex-initial" style="overflow-y: scroll;">
            @if( !empty($result ))
                <img class="w-10 h-10" src="{{url('storage/panel-assets/oracle_bot.png')}}" alt="Oráculo">
                <div>
                    <p class="w-full text-wrap mt-3 w-3/4">{!! $result !!}</p>
                </div>
            @endif
        </div>

        <input class="border-none bg-white shadow-sm bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 rounded-md w-full ps-3 pe-3" type="text" name="prompt" wire:model="prompt" wire:keyup="consultOracle" placeholder="Faça uma pergunta...">
    </div>

    <div class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
        <h3 class="w-full text-center">Sugestões</h3>

        @foreach ($repository as $key => $question)
            @if($key <= 10)
                <div class="flex flex-col w-96 my-2	cursor-pointer p-1 rounded bg-black" >
                    <p class="text-sm truncate-sm ..." wire:click="choseQuestion($event.target.innerText)">{{$question->question}}</p>
                </div>
            @endif
        @endforeach
    </div>
</div>
