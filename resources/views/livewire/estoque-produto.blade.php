
<div>
   
 {{--  <button type="button" wire:click="$set('estoqueModal', true)" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600">Criar Estoque</button>

  <!-- Botão para adicionar um novo produto -->
  <button type="button" wire:click="$set('produtoModal', true)" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Adicionar Produto</button>
              
               
                 
    
                  
                    <div class="flex items-center mt-4">
                      <span class="mr-2 font-medium">Selecionar Estoque:</span>
                      @foreach($this->estoques  as $index => $estoque)
                          <label class="inline-flex items-center mr-4">
                              <input type="radio"  value="{{ $estoque->id }}" wire:model.blur="estoqueId" wire:change="atualizarListaProdutos"  >
                              <span class="ml-1">{{ $estoque->nome }}</span>
                          </label>
                      @endforeach
                  </div>
                                   
            
               
               
                
             
      
    
      <!-- card 2 -->
     
    
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
              <h6 class="dark:text-white">Produtos</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
             
                <table class="items-center justify-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Código</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nome</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantidade</th>
                      <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Validade</th>
                      <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Imagem</th>
                    </tr>
                  </thead>
                  <tbody class="border-t">
                    @if($this->estoqueModel)
                    <div class="flex items-center mt-4">
                      <span class="mr-2 font-medium">Quantidade Mínima:</span>
                      <span class="font-semibold">{{ $this->estoqueModel->quantidade_minima}}</span>
                  
                  </div>

                        
               
                  @endif
                  
                        
                    @foreach($this->produtos ?? [] as $produto)
                 
                    <tr wire:click = "$set('atualizarProduto', true)" >
                        <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                            <div class="flex px-2">
                                <div>
                                    <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58 mr-2"></i>
                                </div>
                                <div class="my-auto">
                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">{{$produto->codigo }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-sm font-semibold leading-normal dark:text-white dark:opacity-60">{{ $produto->nome }}</p>
                        </td>
                        <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                {{ $produto->quantidade }}
                            </span>
                        </td>
                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                {{ $produto->validade }}
                            </span>
                        </td>
                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? 'border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                          <div class="flex justify-center">
                              <img src="http://localhost:8000/{{ $produto->imagem }}" data-te-img="http://localhost:8000/{{ $produto->imagem }}" class="w-24 h-24 md:w-32 md:h-32 lg:w-40 lg:h-40 object-cover transition duration-300 ease-linear mr-2" />
                          </div>
                      </td>
                        <td class="p-2 align-middle bg-transparent{{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                            <button class="inline-block px-5 py-2.5 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none leading-normal text-sm ease-in bg-150 tracking-tight-rem bg-x-25 text-slate-400">
                                <i class="text-xs leading-tight fa fa-ellipsis-v dark:text-white dark:opacity-60"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    
    
                  </tbody>
                  <div class="flex justify-center pb-5" wire:ignore>
          
    
    </div>
                </table>
              
              </div>
             
            </div>
          </div>
        </div>
      </div>
    
      <x-modal.card title="Criar Estoque" blur  wire:model="atualizarModal"  >
       
           
      
      <div class="mb-4">
          <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade Máxima</label>
          <input type="number" id="quantidade" name="quantidade" wire:model="quantidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
      <!-- Botões para enviar ou fechar o modal -->
      <div class="flex justify-end">
          <button type="button" wire:click="atualizarEstoque" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
          <button type="button" wire:click="$set('atualizarModal', false)" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Fechar</button>
      </div>            
      </x-modal.card>

      <x-modal.card title="Criar Estoque" blur  wire:model="estoqueModal"  >
        <div class="mb-4">
          <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
          <input type="text" id="nome" name="nome" wire:model="nome" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
      <div class="mb-4">
          <label for="capacidade" class="block text-sm font-medium text-gray-700">Capacidade</label>
          <input type="number" id="capacidade" name="capacidade" wire:model="capacidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
      <div class="mb-4">
          <label for="quantidade_minima" class="block text-sm font-medium text-gray-700">Quantidade Mínima</label>
          <input type="number" id="quantidade_minima" name="quantidade_minima" wire:model="quantidade_minima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
      <div class="mb-4">
          <label for="quantidade_maxima" class="block text-sm font-medium text-gray-700">Quantidade Máxima</label>
          <input type="number" id="quantidade_maxima" name="quantidade_maxima" wire:model="quantidade_maxima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
      <!-- Botões para enviar ou fechar o modal -->
      <div class="flex justify-end">
          <button type="button" wire:click="criarEstoque" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
          <button type="button" wire:click="$set('estoqueModal', false)" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Fechar</button>
      </div>            
      </x-modal.card>


      <x-modal.card title="Adicionar Produto" blur  wire:model="produtoModal"  >
        <div class="mb-4">
          <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
          <input type="text" id="codigo" name="codigo" wire:model.blur="codigo"   class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      </div>
    @if($this->codigo)
         @if($this->produto)
           {{$this->produto->quantidade_minima}}
             {{$this->produto->estoque->quantidade_maxima}}
             {{$this->produto->estoque->quantidade}}
       <div class="mb-4">
           <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
           <input type="number" id="quantidade" name="quantidade" wire:model.blur="quantidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
         @else
          <div class="mb-4">
          <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
        <input type="text" id="nome" name="nome" wire:model="nome" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
             </div>
          <div class="mb-4">
        <label for="preco" class="block text-sm font-medium text-gray-700">Preço</label>
        <input type="number" id="preco" name="preco" wire:model="preco" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
        <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" wire:model="quantidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
        <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem</label>
        <input type="file" id="imagem" name="imagem" wire:model="imagem" accept="image/*" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
        <label for="dimensao" class="block text-sm font-medium text-gray-700">Dimensão</label>
        <input type="text" id="dimensao" name="dimensao" wire:model="dimensao" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
   
    <div class="mb-4">
        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea id="descricao" name="descricao" wire:model="descricao" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
    </div>
    <div class="mb-4">
        <label for="quantidade_minima" class="block text-sm font-medium text-gray-700">Quantidade Mínima</label>
        <input type="number" id="quantidade_minima" name="quantidade_minima" wire:model="quantidade_minima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
        <label for="validade" class="block text-sm font-medium text-gray-700">Validade</label>
        <input type="date" id="validade" name="validade" wire:model="validade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
        <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
        <input type="text" id="categoria" name="categoria" wire:model="categoria" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
     @endif
  @endif
      <button type="button" wire:click="AdicionarAoEstoque" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
      </x-modal.card>
     --}}

     
<div>
  <button type="button" x-on:click="$openModal('estoqueModal')" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:bg-green-600">Adicionar Estoque</button>
  <div class="w-full px-6 py-6 mx-auto">
    <!-- table 1 -->
  
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Estoques</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
          
              <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                  <tr>
                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nome</th>
                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantidades</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Adicionar Métodos</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Criação</th>
                    <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                  </tr>
                </thead>
                <tbody>
             
                  @foreach($this->estoques as $estoque)
  
                
                  <tr>
                  
                    <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <div class="flex px-2 py-1">
                        <div>
                          <p class="mb-0 text-sm leading-tight dark:text-white dark:opacity-80 text-slate-400">{{  $estoque->nome }}</p>
                        </div>
                        <div class="flex flex-col justify-center">
                         
                        </div>
                      </div>
                    </td>
                    <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                   
                      <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">
                     
                        <h6 class="mb-0 text-sm leading-normal dark:text-white">Quantidade Máxima: {{ $estoque->quantidade_maxima }} </h6>
                        <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">Quantidade Mínima: {{  $estoque->quantidade_minima }}</p>
                      </p>
                    </td>
                    <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <span x-on:click="$openModal('produtoModal')" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white cursor-pointer">Adicionar Produto</span>
                      <span wire:click="deletarEstoque({{ $estoque->id }})" class="bg-gradient-to-tl from-red-500 to-red-600 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white cursor-pointer">Deletar</span>
                    </td>
                   
                    <td class="p-2 text-center align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }} whitespace-nowrap shadow-transparent">
                      <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $estoque->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="p-2 align-middle bg-transparent {{ !$loop->last ? ' border-b dark:border-white/40' : '' }}  whitespace-nowrap shadow-transparent">
                      <input type="radio"   wire:model.change="estoqueModel" value="{{ $estoque->id }}" id="checkbox_{{$estoque->id}}">
                     
                    </td>
                  </tr>
          

                
             
              
         
          
              
                @endforeach
                 
                </tbody>
              </table>
          
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- card 2 -->
   
  
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Produtos</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
           
              

              <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Preço
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Quantidade
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Dimensão
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Código
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Categoria
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Validade
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Descrição
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Imagem
                            </th>
                            <th scope="col" class="px-6 py-3">
                              Ação
                          </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->produtos ?? [] as $produto)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 @if($loop->last) border-b-0 @endif">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-{{ $loop->index }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-table-search-{{ $loop->index }}" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->preco }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->quantidade }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->dimensao }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->codigo }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->categoria }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->validade }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $produto->descricao }}
                            </td>
                            <td class="py-2">
                                <div class="flex justify-center">
                                    <img src="http://localhost:8000/{{ $produto->imagem }}" data-te-img="http://localhost:8000/{{ $produto->imagem }}" class="w-24 h-24 md:w-32 md:h-32 lg:w-40 lg:h-20 object-cover transition duration-300 ease-linear mr-2" />
                                </div>
                            </td>
                            <td class="flex items-center px-2 py-2">
                              <a href="#" wire:click="selecionarProduto({{$produto->id}})"  class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                              <a wire:click="deletarProduto({{$produto->id}})" class="cursor-pointer font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Deletar</a>
                          </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
            </div>
           
          </div>
        </div>
      </div>
    </div>
  
    <x-modal.card title="Adicionar Produto" blur wire:model="produtoModal">
      <div class="grid grid-cols-2 gap-4">
          <div>
              <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
              <input type="text" id="codigo" name="codigo" wire:model.blur="codigo" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div>
              <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
              <input type="number" id="quantidade" name="quantidade" wire:model.blur="quantidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
          
          <div>
              <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
              <input type="text" id="nome" name="nome" wire:model="nome" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div>
              <label for="preco" class="block text-sm font-medium text-gray-700">Preço</label>
              <input type="number" id="preco" name="preco" wire:model="preco" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div>
              <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem</label>
              <input type="file" id="imagem" name="imagem" wire:model="imagem" accept="image/*" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div>
              <label for="dimensao" class="block text-sm font-medium text-gray-700">Dimensão</label>
              <input type="text" id="dimensao" name="dimensao" wire:model="dimensao" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div>
              <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
              <textarea id="descricao" name="descricao" wire:model="descricao" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
          </div>
  
          <div>
              <label for="quantidade_minima" class="block text-sm font-medium text-gray-700">Quantidade Mínima</label>
              <input type="number" id="quantidade_minima" name="quantidade_minima" wire:model="quantidade_minima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div class="mb-6">
              <label for="validade" class="block text-sm font-medium text-gray-700">Validade</label>
              <input type="date" id="validade" name="validade" wire:model="validade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
  
          <div class="mb-6">
              <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
              <input type="text" id="categoria" name="categoria" wire:model="categoria" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
          </div>
      </div>
  
      <button type="button" wire:click="AdicionarAoEstoque" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
  </x-modal.card>
  <x-modal.card title="Criar Estoque" blur  wire:model="estoqueModal"  >
    <div class="mb-4">
      <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
      <input type="text" id="nome" name="nome" wire:model="nome" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
  </div>
  <div class="mb-4">
      <label for="capacidade" class="block text-sm font-medium text-gray-700">Capacidade</label>
      <input type="number" id="capacidade" name="capacidade" wire:model="capacidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
  </div>
  <div class="mb-4">
      <label for="quantidade_minima" class="block text-sm font-medium text-gray-700">Quantidade Mínima</label>
      <input type="number" id="quantidade_minima" name="quantidade_minima" wire:model="quantidade_minima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
  </div>
  <div class="mb-4">
      <label for="quantidade_maxima" class="block text-sm font-medium text-gray-700">Quantidade Máxima</label>
      <input type="number" id="quantidade_maxima" name="quantidade_maxima" wire:model="quantidade_maxima" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
  </div>
  <!-- Botões para enviar ou fechar o modal -->
  <div class="flex justify-end">
      <button type="button" wire:click="criarEstoque" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
      <button type="button" wire:click="$set('estoqueModal', false)" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:bg-gray-400">Fechar</button>
  </div>            
  </x-modal.card>

  <x-modal.card title="Editar Produto" blur  wire:model="editarModal"  >
    <div>
      <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
      <input type="number" id="quantidade" name="quantidade" wire:model.blur="quantidade" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
      <button type="button" wire:click="atualizarEstoque()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Salvar</button>
  </div>        
  </x-modal.card>

    </div>