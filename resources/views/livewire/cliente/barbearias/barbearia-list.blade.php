<div>

  <div class="fixed top-50 w-full bg-[#FBFBFB] shadow-lg z-40">
    <div class="flex gap-y-4 justify-between p-3 lg:p-5">
      <div class="text-left">
        <h1 class="mr-6 text-2xl lg:text-4xl font-bold tracking-tight text-gray-900">Barbearias</h1>
      </div>
<div class="flex flex-col lg:flex-row gap-2 lg:gap-4 w-full lg:w-1/4 items-start lg:items-center">
  <x-input 
    wire:model.live="search" 
    placeholder="Buscar por nome ou cidade" 
    class="w-full lg:w-auto"
  />
  <x-select
    wire:model.live="distancia"
    placeholder="Selecione a distância"
    option-value="id"
    option-label="name"
    :options="$this->opcoesDistancia"
    class="w-full lg:w-auto"
  />
</div>
    </div>
  </div>
  
      <style>

.imagem {
            width: 450px;
            height: 317px;
          }

       @media screen and (max-width: 1680px) and (min-width: 1280px) {
          .grid-cols-custom {
            grid-template-columns: repeat(3, minmax(0, 1fr));
          }

          .imagem {
            width: 500px;
          }


        }

        @media screen and (max-width: 1280px) and (min-width: 1000px) {
          .grid-cols-custom {
            grid-template-columns: repeat(2, minmax(0, 1fr));
          }

          .imagem {
            width: 100%;
          }


        }



      </style>
   <div class="grid pt-[100px]  grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-5 grid-cols-custom ">



  @foreach($this->barbeariasordenadas as $barbearia)


  <div class="relative flex w-full max-w-[40rem] flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-lg" wire:key="list-{{ $barbearia->id }}">
    <div
      class="relative mx-4 mt-4 overflow-hidden text-white shadow-lg rounded-xl bg-blue-gray-500 bg-clip-border shadow-blue-gray-500/40">
      <button    class="cursor-pointer z-[2] absolute top-0 left-0 m-4 h-6 w-6 text-white "       data-te-toggle="modal"
        data-te-target="#exampleModalLabel"

        wire:click ="compartilhar({{$barbearia->id}})">
      <svg     class="transform-rotate-180"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
      </svg>
    </button>
      <img
      src="/storage/{{ $barbearia->imagem }}"
      class=" w-full h-[350px] object-cover"

        alt="ui/ux review check" />
      <div
        class="absolute inset-0 w-full h-full to-bg-black-10 bg-gradient-to-tr from-transparent via-transparent to-black/60">
      </div>

        <livewire:cliente.barbearias.like :barbearia="$barbearia" :key="$barbearia->id" />



    </div>
    <div class="p-6">
      <div class="flex items-center justify-between mb-3">
        <h5 class="block font-sans text-xl antialiased font-medium leading-snug tracking-normal text-blue-gray-900">
          {{ ucfirst($barbearia->nome) }}
      </h5>
        @php
        // Média de avaliações
        $soma = 0;
        $avaliacoes = $barbearia->avaliacoes;

        foreach ($avaliacoes as $ava) {
            $soma += $ava->qtd;
        }

        if ($avaliacoes->count() == 0) {
            $media = 0;
            $percentagemPreenchimento = 0;
        } else {
          $media = number_format($soma / $avaliacoes->count(), 1);

        }
     @endphp


  <ul

  wire:ignore

  class="my-1 flex list-none gap-1 p-0"
>

@for ($i = 1; $i <= 5; $i++)

<li wire:ignore>
    <span
        class="text-black [&>svg]:h-5 [&>svg]:w-5"
        wire:ignore

    >

    @if ($i <= $media)
    <svg xmlns="http://www.w3.org/2000/svg"
    fill="#000000"
         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
    </svg>
    @else
    <svg xmlns="http://www.w3.org/2000/svg"
fill="none"
    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
   <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
</svg>
@endif
    </span>
</li>
@endfor
</ul>



      </div>
      <p class="block font-semibold text-lg antialiased  leading-relaxed text-inherit">
        Rua: {{ $barbearia->rua }}
      </p>
      <p class="block font-sans text-lg antialiased font-semibold leading-relaxed text-inherit">
        Bairro: {{ $barbearia->bairro }}
      </p>
      <p class="block font-sans text-lg antialiased font-semibold leading-relaxed text-inherit">
        Cidade: {{ $barbearia->cidade }}
      </p>
      <p class="block font-sans text-lg antialiased font-semibold leading-relaxed text-inherit">
        Estado: {{ $barbearia->estado }}
      </p>
      <p class="block  font-sans text-lg antialiased  font-semibold leading-relaxed text-inherit">
        CEP: {{ $barbearia->cep }}
      </p>



    </div>
    <div class=" p-6 pt-0">
      <a
          type="button"
          data-te-ripple-init
          data-te-ripple-color="light"
          href="/{{$barbearia->slug}}"
          wire:navigate
          class="rounded w-full text-center bg-black px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#0000] transition duration-150 ease-in-out hover:bg-gray-900 hover:shadow-[0_8px_9px_-4px_rgba(0,0,0,0),0_4px_18px_0_rgba(0,0,0,0)] focus:bg-black focus:shadow-[0_8px_9px_-4px_rgba(0,0,0,0),0_4px_18px_0_rgba(0,0,0,0)] focus:outline-none focus:ring-0 active:bg-black active:shadow-[0_8px_9px_-4px_rgba(0,0,0,0),0_4px_18px_0_rgba(0,0,0,0)]">
          Agendar Agora
      </a>


  </div>
  </div>
  @endforeach
  </div>



    <!-- Modal -->
  <div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"

  id = "exampleModalLabel"
  tabindex="-1"
  wire:ignore.self
  aria-labelledby="exampleModalLabelLg"
  aria-hidden="true">
  <div
    data-te-modal-dialog-ref
    wire:ignore.self
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">


    <div
      class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">


      <div
        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
        <!--Modal title-->
        <h5
          class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
          id="exampleModalLabel">
         Compartilhar
        </h5>
        <!--Close button-->
        <button
          type="button"
          class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
          data-te-modal-dismiss
          aria-label="Close">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-6 w-6">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>


      </div>
  <!-- TW Elements is free under AGPL, with commercial license required for specific uses. See more details: https://tw-elements.com/license/ and contact us for queries at tailwind@mdbootstrap.com -->

      <!--Modal body-->
      <div class="flex flex-col gap-4 items-center justify-center pt-10 pb-10" data-te-modal-body-ref>
        <div class="flex items-center space-x-4">

          <div
    class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
    role="status" wire:loading wire:target="compartilhar">

  </div>

        @if($selectedBarbearia)
        <div wire:loading.remove wire:target="compartilhar" class="flex gap-4">
  <a
  type="button"
  data-te-ripple-init
  data-te-ripple-color="light"
  class="mb-2 inline-block rounded px-6 py-5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"

  href="https://www.facebook.com/sharer/sharer.php?u=http://localhost:8000/{{ $selectedBarbearia->slug }}&quote=Conheça%20a%20barbearia:%20{{ urlencode($selectedBarbearia->nome) }}""
  style="background-color: #1877f2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    class="h-4 w-4"
    fill="currentColor"
    viewBox="0 0 24 24">
    <path
      d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
  </svg>
  </a>

  <!-- Instagram -->
  <button
  type="button"
  data-te-ripple-init
  data-te-ripple-color="light"
  class="mb-2 inline-block rounded px-6 py-5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
  style="background-color: #c13584">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    class="h-4 w-4"
    fill="currentColor"
    viewBox="0 0 24 24">
    <path
      d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
  </svg>
  </button>

  <!-- Google -->
  <a


      href="https://twitter.com/intent/tweet?url=http://localhost:8000/{{ $selectedBarbearia->slug }}&text=Conheça a  barbearia: {{ $selectedBarbearia->nome }}"

    target="_blank"
  data-te-ripple-init
  data-te-ripple-color="light"
  class="mb-2 inline-block rounded px-6 py-5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg cursor-pointer"
  style="background-color: black">
  <i class="fa-brands fa-x-twitter fa-lg"></i>
  </a>

  <a

  data-te-ripple-init
  target="_blank"
  data-te-ripple-color="light"
  href="https://wa.me/?text=Confira%20esta%20barbearia: http://localhost:8000/{{ $selectedBarbearia->slug }}&app_absent=0"
  class="mb-2 inline-block rounded px-6 py-5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
  style="background-color: #128c7e">
  <svg
  xmlns="http://www.w3.org/2000/svg"
  class="h-4 w-4"
  fill="currentColor"
  viewBox="0 0 24 24">
  <path
    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
  </svg>
  </a>


  </div>



  @endif

        </div>
        <div
    id="container-example"
    class="fixed right-0 top-0 z-[2000] mr-3 mt-[59px] hidden w-1/4 items-center rounded-lg bg-primary-100 px-6 py-4 text-base text-primary-800 data-[te-alert-show]:inline-flex"
    role="alert"
    data-te-alert-init
    data-te-autohide="true"
    data-te-delay="1000">
    Text copied!
  </div>

        <div class="flex flex-col gap-2 items-center" wire:loading.remove wire:target="compartilhar">
          <button
            id="copy-button"
            type="button"
            data-te-clipboard-init
            data-te-clipboard-target="#copy-target-2"
            data-te-ripple-init
            data-te-ripple-color="light"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
            Copiar Link
          </button>
          @if($selectedBarbearia)
          <div id="copy-target-2" class=" hidden border border-blue-600 rounded-md  p-5 mt-2">http://localhost:8000:8000/{{ $selectedBarbearia->slug }}</div>
          @endif
          </div>
      </div>




      <!--Modal footer-->
      <div
        class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
        <button
          type="button"
          class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-primary-700 transition duration-150 ease-in-out hover:bg-primary-accent-100 focus:bg-primary-accent-100 focus:outline-none focus:ring-0 active:bg-primary-accent-200"
          data-te-modal-dismiss
          data-te-ripple-init
          data-te-ripple-color="light">
          Fechar
        </button>

      </div>

    </div>
</div>

  </div>
  @script
<script>
document.addEventListener('livewire:navigated', function () {
      console.log("📍 Script Livewire carregado!");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
            const component = window.Livewire.find(@this.id); // @this.id pega o id do componente atual
                if(component) {
                      component.dispatch('setLocation',{ lat: position.coords.latitude, lng: position.coords.longitude });
                    console.log("📍 Localização enviada:", position.coords.latitude, position.coords.longitude);
                } else {
                    console.error("❌ Componente Livewire não encontrado");
                }

            },
            function (error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        alert("Você negou o acesso à sua localização.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Informações de localização indisponíveis.");
                        break;
                    case error.TIMEOUT:
                        alert("Tempo limite para obter localização expirou.");
                        break;
                    default:
                        alert("Ocorreu um erro desconhecido ao obter sua localização.");
                        break;
                }
            }
        );
    } else {
        alert("Seu navegador não suporta geolocalização.");
    }
});
</script>
@endscript

</div>
