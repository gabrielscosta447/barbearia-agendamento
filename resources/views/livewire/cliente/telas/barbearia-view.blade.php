

<div x-data="{ shown: false, shownHome: false ,shownServico: false,shownBarbeiro : false, shownComentario:false}" class="overflow-hidden">

  @use (Carbon\Carbon)




<!-- Container for demo purpose -->



{{--   <!-- Navbar -->
  <nav
    class="sticky top-0 z-10 flex w-full items-center justify-between bg-white py-2 text-neutral-600 shadow-lg hover:text-neutral-700 focus:text-neutral-700 dark:bg-neutral-600 dark:text-neutral-200 md:flex-wrap md:justify-start"
    data-te-navbar-ref>
    <div class="px-6">
      <!-- Hamburger menu button -->
      <button
        class="border-0 bg-transparent px-2 py-3 text-xl leading-none transition-shadow duration-150 ease-in-out hover:text-neutral-700 focus:text-neutral-700 dark:hover:text-white dark:focus:text-white md:hidden"
        type="button"
        data-te-collapse-init
        data-te-target="#navbarSupportedContentE"
        aria-controls="navbarSupportedContentE"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <!-- Hamburger menu icon -->
        <span class="[&>svg]:w-5">
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
              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </span>
      </button>

      <!-- Navigation links -->
      <div
        class="!visible hidden grow basis-[100%] items-center md:!flex md:basis-auto"
        id="navbarSupportedContentE"
        data-te-collapse-item>
        <ul
          class="mr-auto flex flex-col md:flex-row"
          data-te-navbar-nav-ref>
          <li data-te-nav-item-ref>
            <a
              class="block transition duration-150 ease-in-out hover:text-neutral-700 focus:text-neutral-700 dark:hover:text-white dark:focus:text-white md:p-2 [&.active]:border-primary [&.active]:text-primary"
              href="#!"
              data-te-nav-link-ref
              data-te-ripple-init
              data-te-ripple-color="light"
              >Agendar</a
            >
          </li>
          <li data-te-nav-item-ref>
            <a
              class="block transition duration-150 ease-in-out hover:text-neutral-700 focus:text-neutral-700 dark:hover:text-white dark:focus:text-white md:p-2 [&.active]:border-primary [&.active]:text-primary"
              href="#galeria"
              data-te-smooth-scroll-init
              data-te-easing="easeInOutQuart"

              >Galeria</a
            >
          </li>
          <li data-te-nav-item-ref>
            <a
              class="block transition duration-150 ease-in-out hover:text-neutral-700 focus:text-neutral-700 dark:hover:text-white dark:focus:text-white md:p-2 [&.active]:border-primary [&.active]:text-primary"
              href="#!"
              data-te-nav-link-ref
              data-te-ripple-init
              data-te-ripple-color="light"
              >Serviços</a
            >
          </li>
          <li data-te-nav-item-ref>
            <a
              class="block transition duration-150 ease-in-out hover:text-neutral-700 focus:text-neutral-700 dark:hover:text-white dark:focus:text-white md:p-2 [&.active]:border-primary [&.active]:text-primary "
              href="#!"
              data-te-nav-link-ref
              data-te-ripple-init
              data-te-ripple-color="light"
              >Comentários</a
            >
          </li>
        </ul>
      </div>
    </div>
  </nav> --}}



<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/home" class="flex items-center space-x-3 rtl:space-x-reverse" wire:navigate>
        <img src="{{ asset('barbearia.png') }}" class="w-[180px] h-[40px] object-cover"  alt="Flowbite Logo" />

    </a>
    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" @if(auth()->user() && isset(auth()->user()->profile_photo_url)) src="{{ auth()->user()->profile_photo_url }}" @endif alt="user photo">
        </button>
        <!-- Dropdown menu -->
        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
          <div class="px-4 py-3">
            <span class="block text-sm text-gray-900 dark:text-white">Bonnie Green</span>
            <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">name@flowbite.com</span>
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
            </li>
          </ul>
        </div>
        <button     data-te-collapse-init    data-te-target="#navbarSupportedContent2"
        aria-controls="navbarSupportedContent2"
        aria-expanded="false"
        aria-label="Toggle navigation" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
          </svg>
      </button>
    </div>
    <div class="items-center justify-between hidden w-full lg:mr-32 md:flex md:w-auto md:order-1"     id="navbarSupportedContent2"  data-te-collapse-item >
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="#home"   data-te-duration="1000"  data-te-easing="easeInOutQuart"  class=" linkSmooth block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
          :class="{ 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500': shownHome}"
          aria-current="page">Home</a>
        </li>
        <li>
          <a href="#galeria"  data-te-duration="1000"  data-te-easing="easeInOutQuart"
          class="linkSmooth block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700  dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
          :class="{ 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500': shown}"
          aria-current="page">Galeria</a>
        </li>
        <li>
          <a href="#services"   data-te-duration="1000"  data-te-easing="easeInOutQuart"   class="linkSmooth block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 ":class="{ 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500': shownServico}">Serviços</a>
        </li>
        <li>
          <a href="#barbeiro"    data-te-duration="1000"  data-te-easing="easeInOutQuart" class="linkSmooth block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700" :class="{ 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500': shownBarbeiro}">Barbeiros</a>
        </li>
        <li>
          <a href="#comentario" id="comentarioLink" data-te-duration="1000"  data-te-easing="easeInOutQuart"   class=" linkSmooth block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700" :class="{ 'block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500': shownComentario}">Comentários</a>
        </li>
      </ul>
    </div>
    </div>
</nav>


  <!-- Hero section with background image, heading, subheading and button -->
  <div
    class="relative overflow-hidden  mx-auto bg-cover bg-center bg-no-repeat p-12 text-center "
    id="home"

x-intersect="shownHome = true;"
    x-intersect:leave="shownHome =  false"
    style="
      background-image: url('http://localhost:8000/6c082904e4c74374b352ad53b2b2a8be (1) (1).png');
      height: 850px;


    "

    >
{{--     Versão Homem: http://localhost:8000/6c082904e4c74374b352ad53b2b2a8be-_1_-_1_-min.webp
    Versão Mulher: salao de beleza 76ee1d75-c173-4ac8-9b66-26ef60913419 (1).png --}}
    <div
=
      class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-fixed"
      style="background-color: rgba(0, 0, 0, 0.6)">
      <div class="flex h-full items-center justify-center" >
        <div class="text-white">
          <h2 class="mb-4 text-4xl font-semibold">{{ $barbearia->nome }}</h2>
          <h4 class="mb-6 text-xl font-semibold">{{ $barbearia->cidade }}, {{ $barbearia->estado }}</h4>
          <button
            type="button"
            data-te-toggle="modal"
            data-te-target="#exampleModalLg"
            class="rounded border-2 border-neutral-50 px-7 pb-[8px] pt-[10px] text-sm font-medium uppercase leading-normal text-neutral-50 transition duration-150 ease-in-out hover:border-neutral-100 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-neutral-100 focus:border-neutral-100 focus:text-neutral-100 focus:outline-none focus:ring-0 active:border-neutral-200 active:text-neutral-200 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10"
            data-te-ripple-init
            data-te-ripple-color="light"

            >
       AGENDAR AGORA
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Container for demo purpose -->
<div class="container my-16 mx-auto md:px-6"    "   id="galeria">

  <div  x-intersect="shown = true">
  <h3      class="text-4xl text-center font-bold mb-4">Galeria</h3>
  <hr class="h-[2px] bg-gray-100  my-10 border-none"     />
</div>
<div

data-te-lightbox-init
class="flex flex-col space-y-5 lg:flex-row lg:space-x-5 lg:space-y-0">
{{-- <div class="h-full w-full">
  <img
    src="/barbearia.avif"
    data-te-img="/barbearia.avif"
    alt="Table Full of Spices"
    class="w-full cursor-zoom-in rounded shadow-sm data-[te-lightbox-disabled]:cursor-auto" />
</div>
<div class="h-full w-full">
  <img
    src="/barbearia.avif"
    data-te-img="/barbearia.avif"
    alt="Winter Landscape"
    class="w-full cursor-zoom-in rounded shadow-sm data-[te-lightbox-disabled]:cursor-auto" />
</div>
<div class="h-full w-full">
  <img
  src="/barbearia.avif"
    data-te-img="/barbearia.avif"
    alt="View of the City in the Mountains"
    class="w-full cursor-zoom-in rounded shadow-sm data-[te-lightbox-disabled]:cursor-auto" />
</div> --}}
@can('create',$barbearia)

<div class="mx-auto cursor-pointer" x-on:click="$openModal('galeriaModal')">
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" version="1.1" id="Capa_1" width="100px" height="100px" viewBox="0 0 45.402 45.402" xml:space="preserve">
    <g>
      <path d="M41.267,18.557H26.832V4.134C26.832,1.851,24.99,0,22.707,0c-2.283,0-4.124,1.851-4.124,4.135v14.432H4.141   c-2.283,0-4.139,1.851-4.138,4.135c-0.001,1.141,0.46,2.187,1.207,2.934c0.748,0.749,1.78,1.222,2.92,1.222h14.453V41.27   c0,1.142,0.453,2.176,1.201,2.922c0.748,0.748,1.777,1.211,2.919,1.211c2.282,0,4.129-1.851,4.129-4.133V26.857h14.435   c2.283,0,4.134-1.867,4.133-4.15C45.399,20.425,43.548,18.557,41.267,18.557z"/>
    </g>
    </svg>
</div>




<x-modal.card title="Adicionar items" blur wire:model.defer="galeriaModal">
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


      <div class="col-span-1 sm:col-span-2">

      </div>

      <div class="col-span-1 sm:col-span-2 cursor-pointer bg-gray-100 rounded-xl shadow-md h-72 flex items-center justify-center">

            <label for="fotos" class="flex flex-col items-center justify-center cursor-pointer">
              <x-icon name="cloud-upload" class="w-16 h-16 text-blue-600" />
              <p class="text-blue-600">Click or drop files here</p>
            </label>

      </div>
  </div>
  <input type="file" id="fotos" multiple wire:model="fotos"  class="sr-only">

@forelse($fotos as $foto)
<div class="mb-4 mt-3">
  <img src="{{ $foto->temporaryUrl() }}"  class="max-w-full h-auto rounded-lg">
  <x-input wire:model="descricao.{{ $loop->index }}" label="Descrição" placeholder="Descrição" class="mt-3 " />
</div>

@empty


@endforelse
  <x-slot name="footer">
      <div class="flex justify-between gap-x-4">
          <x-button flat negative label="Delete" wire:click="delete" />

          <div class="flex">
              <x-button flat label="Cancel" x-on:click="close" />
              <x-button primary label="Save" wire:click="salvarGaleria" />
          </div>
      </div>
  </x-slot>
</x-modal.card>
@endcan
<div
  data-te-lightbox-init
  x-intersect="shown = true"
  x-intersect:leave="shown = false"
  :class="shown ? 'transition translate-x-none opacity-1 duration-1000' : 'transition translate-x-3/4 opacity-0 duration-1000'"
  x-transition:leave="transition ease-in duration-1000"
  x-transition:leave-start="opacity-1 translate-x-none"
  x-transition:leave-end="opacity-0 translate-x-3/4"
  class="grid gap-6 lg:grid-cols-3 mx-auto"
  wire:ignore.self
>
@foreach(array_slice($this->galeria, 0, 6) as $galeria)
<div class="zoom relative overflow-hidden rounded-lg bg-cover bg-no-repeat shadow-lg dark:shadow-black/20 bg-[50%]" data-te-ripple-init data-te-ripple-color="dark" >
    @if(isset($galeria['media_url']))
        <img src="{{ $galeria['media_url'] }}" data-te-caption="{{ $galeria['caption'] }}" data-te-img="{{ $galeria['media_url'] }}" class="w-[400px] h-[200px] object-cover align-middle transition duration-300 ease-linear" />
    @else
        <img src="http://localhost:8000/storage/{{ $galeria['foto'] }}" data-te-caption="{{ $galeria['descricao'] }}" data-te-img="http://localhost:8000/storage/{{ $galeria['foto'] }}" class="w-[400px] max-h-[400px]   xl:min-h-[350px] lg:min-h-[250px]  object-cover align-middle transition duration-300 ease-linear" />
    @endif
</div>
@endforeach
</div>

</div>
  </div>
<div class="flex justify-center">
<button
data-te-toggle="modal"
data-te-target="#exampleModalLg2"
data-te-ripple-init
data-te-ripple-color="light"
  type="button"
  class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
Galeria Completa
</button>
</div>

<div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
  id="exampleModalLg2"
  tabindex="-1"
  aria-labelledby="exampleModalLgLabel"
  aria-modal="true"
  role="dialog">
  <div
    data-te-modal-dialog-ref
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px] min-[992px]:max-w-[800px]">
    <div
      class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
      <div
        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
        <!--Modal title-->
        <h5
          class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
          id="exampleModalLgLabel">
       Galeria Completa
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

      <!--Modal body-->
      <div class="relative  overflow-y-auto p-4">
        <div  data-te-lightbox-init wire:ignore.self class="grid gap-4 lg:grid-cols-3 mx-auto">
          @foreach($this->galeria as $galeria)

          <div class="zoom relative overflow-hidden rounded-lg bg-cover bg-no-repeat shadow-lg dark:shadow-black/20 bg-[50%]" data-te-ripple-init data-te-ripple-color="dark" >
              @if(isset($galeria['media_url']))
              <img  src="{{ $galeria['media_url'] }}"   data-te-caption="{{ $galeria['caption'] }}" data-te-img="{{ $galeria['media_url'] }}" class="w-[400px]  object-cover align-middle transition duration-300 ease-linear" />
              @else

              <img  src="http://localhost:8000/storage/{{ $galeria['foto'] }}"   data-te-caption="{{ $galeria['descricao'] }}" data-te-img="http://localhost:8000/storage/{{ $galeria['foto'] }}" class="w-full max-h-[200px]    object-cover align-middle transition duration-300 ease-linear" />
              @endif
          </div>
       @endforeach
    </div>
      </div>
    </div>
  </div>
</div>

<div class="container my-24 mx-auto md:px-6" x-intersect = "shownServico=true" x-intersect:leave = "shownServico = false"  id="services" >
  <!-- Section: Design Block -->
  <section class="mb-32 text-center md:text-left" >
    <div
      class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
      <div class="flex flex-wrap items-center">
        <div class="block w-full shrink-0 grow-0 basis-auto lg:flex lg:w-6/12 xl:w-4/12">
          <img src="http://localhost:8000/storage/{{ $barbearia->imagem }}" alt="Trendy Pants and Shoes"
            class="w-full h-[400px] object-cover  rounded-t-lg lg:rounded-tr-none lg:rounded-bl-lg" />
        </div>
        <div class="w-full shrink-0 grow-0 basis-auto lg:w-6/12 xl:w-8/12">
          <div class="px-6 py-12 md:px-12">
            <h2 class="mb-6 pb-2 text-3xl font-bold">
            Os melhores serviços
            </h2>
            <p class="mb-6 pb-2 text-neutral-500 dark:text-neutral-300">
             Corte com os melhores barbeiros  e com os melhores serviços.
            </p>
            <div class="mb-6 flex flex-wrap">
              @foreach($barbearia->barbeiros as $barbeiro)
              @foreach($barbearia->cortes as $corte)
              <div class="mb-4 w-full md:w-4/12">
                <p class="flex">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="mr-3 h-5 w-5 text-neutral-900 dark:text-neutral-50">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $corte->nome }}
                </p>
              </div>
             @endforeach
             @endforeach
            </div>
            <button type="button"
            data-te-toggle="modal"
            data-te-target="#exampleModalLg"
              class="inline-block rounded bg-neutral-800 px-12 pt-3.5 pb-3 text-sm font-medium uppercase leading-normal text-neutral-50 shadow-[0_4px_9px_-4px_rgba(51,45,45,0.7)] transition duration-150 ease-in-out hover:bg-neutral-800 hover:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] focus:bg-neutral-800 focus:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] dark:bg-neutral-50 dark:text-neutral-800 dark:shadow-[0_4px_9px_-4px_rgba(251,251,251,0.3)] dark:hover:shadow-[0_8px_9px_-4px_rgba(251,251,251,0.1),0_4px_18px_0_rgba(251,251,251,0.05)] dark:focus:shadow-[0_8px_9px_-4px_rgba(251,251,251,0.1),0_4px_18px_0_rgba(251,251,251,0.05)] dark:active:shadow-[0_8px_9px_-4px_rgba(251,251,251,0.1),0_4px_18px_0_rgba(251,251,251,0.05)]"
              data-te-ripple-init data-te-ripple-color="light">
              Agendar Agora
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Section: Design Block -->
</div>
  <div class="flex justify-center">
    @can('create',$barbearia)
    <a
    type="button"
   href="/gerenciar/{{$barbearia->slug}}"
    class=" w-[300px] text-center inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-[0_4px_9px_-4px_rgba(51,45,45,0.7)] transition duration-150 ease-in-out hover:bg-neutral-800 hover:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] focus:bg-neutral-800 focus:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-[0_8px_9px_-4px_rgba(51,45,45,0.2),0_4px_18px_0_rgba(51,45,45,0.1)] dark:bg-neutral-900 dark:shadow-[0_4px_9px_-4px_#030202] dark:hover:bg-neutral-900 dark:hover:shadow-[0_8px_9px_-4px_rgba(3,2,2,0.3),0_4px_18px_0_rgba(3,2,2,0.2)] dark:focus:bg-neutral-900 dark:focus:shadow-[0_8px_9px_-4px_rgba(3,2,2,0.3),0_4px_18px_0_rgba(3,2,2,0.2)] dark:active:bg-neutral-900 dark:active:shadow-[0_8px_9px_-4px_rgba(3,2,2,0.3),0_4px_18px_0_rgba(3,2,2,0.2)]">
         Gerenciar Barbearia
  </a>
  @else
<!-- Button trigger modal -->

@endcan


<livewire:cliente.agendamentos.agendar-barbearia :barbearia="$barbearia" :key="$barbearia->id" />






</div>

  <div class="container my-24 mx-auto md:px-6 " id="barbeiro" x-intersect = "shownBarbeiro =true" x-intersect:leave = "shownBarbeiro =false">
    <!-- Section: Design Block -->
    <section class="mb-32 text-center">
        <h2 class="mb-12 text-3xl font-bold">
            Encontre os <u class="text-black">barbeiros</u>
        </h2>

        <div class="grid gap-x-6 md:grid-cols-3 lg:gap-x-12">
            @foreach($barbearia->barbeiros as $barbeiro)
            <div class="mb-6 lg:mb-0">
                <div class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
                    <div class="relative overflow-hidden bg-cover bg-no-repeat">
                        <img src="{{ $barbeiro->user->profile_photo_url }}" class="w-full h-[350px] rounded-t-lg  object-cover" />
                        <a href="#!">
                            <div class="absolute top-0 right-0 bottom-0 left-0 h-full w-full overflow-hidden bg-fixed"></div>
                        </a>
                        <svg class="absolute text-white left-0 bottom-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="currentColor" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,250.7C1248,256,1344,288,1392,304L1440,320L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h5 class="mb-4 text-lg font-bold">{{ $barbeiro->name }}</h5>
                        <p class="mb-3 text-neutral-500">Serviços:  @foreach($barbeiro->cortes as $corte)
                          @if($loop->index < 3) <!-- Limita a exibição aos 3 primeiros cortes -->
                           {{ $corte->corte->nome }}
                          @endif
                      @endforeach</p>
                        <p class="mb-3 text-neutral-500">Horários de Trabalho:</p>

                        @foreach($barbeiro->workingHours as $workingHour)
                        <p class="mb-3 text-neutral-500">{{ $workingHour->day_of_week->name }}: {{ \Carbon\Carbon::parse($workingHour->start_hour)->format('H:i') }} - {{ \Carbon\Carbon::parse($workingHour->end_hour)->format('H:i') }}</p>
                        @endforeach
                        <ul class="mx-auto flex list-inside justify-center">
                            <a href="#!" class="px-2">
                                <!-- GitHub -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 text-primary">
                                    <path fill="currentColor" d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                </svg>
                            </a>
                            <a href="#!" class="px-2">
                                <!-- Twitter -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 text-primary">
                                    <path fill="currentColor" d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#!" class="px-2">
                                <!-- Linkedin -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5 text-primary">
                                    <path fill="currentColor" d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                                </svg>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    <!-- Section: Design Block -->
</div>




<div id="comentario" x-intersect="shownComentario = true" x-intersect:leave="shownComentario = false">
    <livewire:cliente.barbearias.comentarios :barbearia="$barbearia" :key="'comentarios-'.$barbearia->id" />
</div>



<footer
  class="flex flex-col items-center bg-neutral-900 text-center text-white">
  <div class="container px-6 pt-6">
    <!-- Social media icons container -->
    <div class="mb-6 flex justify-center">

      @foreach($barbearia->redes_sociais ?? [] as $index => $redeSocial)

    @switch($index)
        @case('Facebook')
        <a
        href="{{ $redeSocial }}"
        target="__blank"
        type="button"
        class="m-1 h-9 w-9 rounded-full border-2 border-white uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0"
        data-te-ripple-init
        data-te-ripple-color="light">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="mx-auto h-full w-4"
          fill="currentColor"
          viewBox="0 0 24 24">
          <path
            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
        </svg>
      </a>
        @break
        @case('Instagram')
        <a
        href="{{ $redeSocial}}"
        target="__blank"
        type="button"
        class="m-1 h-9 w-9 rounded-full border-2 border-white uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0"
        data-te-ripple-init
        data-te-ripple-color="light">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="mx-auto h-full w-4"
          fill="currentColor"
          viewBox="0 0 24 24">
          <path
            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
        </svg>
      </a>
            @break
        @case('X')
            <a href="{{ $redeSocial }}" target="__blank" type="button" class="m-1 h-9 w-9 rounded-full border-2 border-white uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0" data-te-ripple-init data-te-ripple-color="light">

                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                            class="mx-auto h-full w-4"
                      viewBox="0 0 512 512">
                      <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
                      <path
                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                    </svg>

            </a>
            @break
      @case('Youtube')
      <a href="{{ $redeSocial}}" target="__blank" type="button" class="m-1 h-9 w-9 rounded-full border-2 border-white uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0" data-te-ripple-init data-te-ripple-color="light">
        <svg     xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M12 22q-2.075 0-3.9-.788q-1.825-.787-3.175-2.137q-1.35-1.35-2.137-3.175Q2 14.075 2 12t.788-3.9q.787-1.825 2.137-3.175q1.35-1.35 3.175-2.138Q9.925 2 12 2t3.9.787q1.825.788 3.175 2.138q1.35 1.35 2.137 3.175Q22 9.925 22 12t-.788 3.9q-.787 1.825-2.137 3.175q-1.35 1.35-3.175 2.137Q14.075 22 12 22Zm0-2.5q3.125 0 5.312-2.188Q19.5 15.125 19.5 12q0-3.125-2.188-5.312Q15.125 4.5 12 4.5q-3.125 0-5.312 2.188Q4.5 8.875 4.5 12q0 3.125 2.188 5.312Q8.875 19.5 12 19.5Zm0-1.5q-2.5 0-4.25-1.75T6 12q0-2.5 1.75-4.25T12 6q2.5 0 4.25 1.75T18 12q0 2.5-1.75 4.25T12 18Zm-2-2.5l5.5-3.5L10 8.5Z"/></svg>
    </a>
      @break
    @endswitch
@endforeach


@can('create', $barbearia)
 <button
  type="button"
  x-on:click="$openModal('cardModal')"
  class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
   Adicionar Redes Sociais
</button>
@endcan

{{-- <button
  type="button"
  wire:click="importarGaleria"
  class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
  Button
</button> --}}

    </div>

    <!-- Newsletter sign-up form -->
    <div>
      <form action="">
        <div
          class="m-auto">


          <!-- Newsletter sign-up input field -->


          <!-- Newsletter sign-up submit button -->
          <div class="mb-6 md:mr-auto">
            <button
              type="button"
              class="inline-block rounded border-2 border-neutral-50 px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-neutral-50 transition duration-150 ease-in-out hover:border-neutral-100 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-neutral-100 focus:border-neutral-100 focus:text-neutral-100 focus:outline-none focus:ring-0 active:border-neutral-200 active:text-neutral-200 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10"
              data-te-ripple-init
              data-te-ripple-color="light">
             Inscreva-se
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Copyright information -->
    <div class="mb-6">
        <p>
            A Barbearia <strong>{{ $barbearia->nome }}</strong> é um verdadeiro oásis para quem busca não apenas um corte de cabelo ou barba impecáveis, mas também uma experiência de bem-estar e cuidado pessoal. Desde o momento em que você entra, é recebido pela atmosfera acolhedora e pelo ambiente cuidadosamente projetado, que combina estilo contemporâneo com um toque clássico.


            Os profissionais altamente qualificados não apenas dominam as técnicas mais recentes de barbearia, mas também entendem a importância de um atendimento personalizado. Cada visita à Barbearia {{ $barbearia->nome }} é uma oportunidade de relaxar e desfrutar de serviços que vão além das expectativas, desde cortes de cabelo precisos até tratamentos de barba que deixam os clientes com uma aparência e sensação renovadas.


            Além disso, o ambiente é complementado por uma seleção cuidadosa de produtos de alta qualidade, garantindo que cada serviço não só atenda, mas supere as expectativas dos clientes mais exigentes.
        </p>
    </div>

    <!-- Links section -->
    <div class="flex flex-col justify-center mt-2">
      <div class="mb-6">
        <h5 class="mb-2.5 font-bold uppercase">Serviços da Barbearia</h5>

        <ul class="mb-0 list-none ">

          @forelse($barbearia->cortes as $corte)
          <li>
            <p  class="text-white">{{ $corte->nome }}</p>
          </li>
           @empty
           <li>
            <p  class="text-white">Nenhum serviço encontrado.</p>
          </li>
          @endforelse

        </ul>
      </div>


    </div>


  <!-- Copyright section -->
  <div
    class="w-full p-4 text-center"
   ">
    © 2024 Copyright:
    <a class="text-white" href="https://tw-elements.com/">BarberConnect</a>
  </div>
</footer>
@can('create',$barbearia)
<x-modal.card  max-width="3xl" title="Adicionar Rede Social" blur wire:model.defer="cardModal">

  <x-select
      class="mb-3"
      label="Selecionar Redes Sociais"
      autocomplet="off"
      multiselect
      placeholder="Selecionar Redes Sociais"
      wire:model.blur="redesocial"
  >
      <x-select.user-option src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" label="Instagram" value="Instagram" />
      <x-select.user-option src="https://cdn-icons-png.flaticon.com/512/145/145802.png" label="Facebook" value="Facebook" />
      <x-select.user-option src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4a6y8M9WPSWOIpkx-XeDBttgxLcV5As2lZfZEuV-ku2k236l7pqNZIFHlftNEKembwzs&usqp=CAU" label="X" value="X" />
      <x-select.user-option src="https://via.placeholder.com/500" label="Youtube" value="Youtube" />
  </x-select>


  @foreach($this->redesocial ?? [] as $index => $rede)
      <x-input label="{{ $rede }} - Link" wire:model="link.{{ $index }}" placeholder="Link"  class="mb-4" />
  @endforeach

  <x-slot name="footer">
      <div class="flex justify-between gap-x-4">
          <x-button flat negative label="Delete" wire:click="delete" />

          <div class="flex">
              <x-button flat label="Cancelar" x-on:click="close" />
              <x-button primary label="Salvar" wire:click="save" />
          </div>
      </div>
  </x-slot>
</x-modal.card>
@endcan
@assets

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
@endassets



  <script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";







  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
  apiKey: "AIzaSyA4CQogWgwcJfRi3c31j5oDWG_HI2GJ_CY",
  authDomain: "barbearia-agendamento-7fe43.firebaseapp.com",
  projectId: "barbearia-agendamento-7fe43",
  storageBucket: "barbearia-agendamento-7fe43.appspot.com",
  messagingSenderId: "1043760496155",
  appId: "1:1043760496155:web:cced72387333a32fa482be",
  measurementId: "G-3VPXJXJWVQ"
};
















const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

navigator.serviceWorker.register("/firebase-messaging-sw.js").then(registration => {
  getToken(messaging, {
      serviceWorkerRegistration: registration,
      vapidKey: 'BFekMqC6gz42tCCpKVrxzqn6ScEQ5xRdXbxfIQxltloZduToLcJawLDAokGmYUGagHQnBERn4oRx5rI7luYx5YA'
  }).then((currentToken) => {
      if (currentToken) {
          console.log(currentToken);
          console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
          // Enviar o token para a rota /user/token
          fetch('/nova', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ token: currentToken })
          }).then(response => {
              if (response.ok) {
                  console.log('Token enviado com sucesso.');
              } else {
                  console.log('Falha ao enviar o token.');
              }
          }).catch((error) => {
              console.log('Erro ao enviar o token:', error);
          });
      } else {
          // Show permission request UI
          console.log('No registration token available. Request permission to generate one.');
          // ...
      }
  }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
      // ...
  });
});


</script>







</div>
