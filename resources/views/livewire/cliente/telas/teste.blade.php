
@use('App\Models\Plan')
<div >




    <!-- TW Elements is free under AGPL, with commercial license required for specific uses. See more details: https://tw-elements.com/license/ and contact us for queries at tailwind@mdbootstrap.com -->
<!-- Main navigation container -->
<nav
  class="sticky top-0 z-10 w-full bg-[#FBFBFB] shadow-lg py-2 text-neutral-500 dark:bg-neutral-600 lg:py-4"
  data-te-navbar-ref
>
  <div class="flex w-full flex-wrap items-center justify-between px-3">

    <!-- Logo -->
    <div class="flex items-center">
      <a
        class="hidden lg:block"
        data-te-offcanvas-toggle
        href="#offcanvasExample"
        role="button"
        aria-controls="offcanvasExample"
        data-te-ripple-init
        data-te-ripple-color="light"
      >
        <img src="{{ asset('barbearia.png') }}" class="w-[180px] h-[40px] object-cover" alt="Logo" />
      </a>
    </div>

    <!-- Hamburger button for mobile view -->
    <button
      class="block lg:hidden border-0 bg-transparent px-2 text-neutral-500 hover:no-underline focus:outline-none"
      type="button"
      data-te-collapse-init
      data-te-target="#navbarSupportedContent2"
      aria-controls="navbarSupportedContent2"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-7 w-7">
        <path fill-rule="evenodd" d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
      </svg>
    </button>

    <!-- Collapsible navbar container -->
    <div
      class="hidden w-full flex-col lg:flex lg:flex-row lg:items-center lg:w-auto mt-2 lg:mt-0"
      id="navbarSupportedContent2"
      data-te-collapse-item
    >
      <!-- Left links -->
      <ul
        id="myTab"
        class="flex flex-col md:flex-row list-none pl-0 md:ml-5 gap-2 md:gap-0"
        role="tablist"
        data-te-nav-ref
      >
        <li role="presentation">
          <a href="#pills-home7"
             class="block rounded bg-neutral-100 px-4 pb-2 pt-2 text-xs font-medium uppercase text-neutral-500 data-[te-nav-active]:!bg-neutral-800 data-[te-nav-active]:text-neutral-50 dark:bg-neutral-700 dark:text-white dark:data-[te-nav-active]:!bg-neutral-900 dark:data-[te-nav-active]:text-neutral-50 md:mr-4"
             id="pills-home-tab7"
             data-te-toggle="pill"
             data-te-target="#pills-home7"
             @if($tab === 'pills-home7') data-te-nav-active @endif
             role="tab"
             wire:click="selecionarTab('pills-home7')"
             aria-controls="pills-home7"
             aria-selected="true"
             wire:ignore.self
          >Barbearias</a>
        </li>
        <li role="presentation">
          <a href="#pills-profile7"
             class="block rounded bg-neutral-100 px-4 pb-2 pt-2 text-xs font-medium uppercase text-neutral-500 data-[te-nav-active]:!bg-neutral-800 data-[te-nav-active]:text-neutral-50 dark:bg-neutral-700 dark:text-white dark:data-[te-nav-active]:!bg-neutral-900 dark:data-[te-nav-active]:text-neutral-50 md:mr-4"
             id="pills-profile-tab7"
             data-te-toggle="pill"
             @if($tab === 'pills-profile7') data-te-nav-active @endif
             data-te-target="#pills-profile7"
             role="tab"
             wire:click="selecionarTab('pills-profile7')"
             aria-controls="pills-profile7"
             aria-selected="false"
             wire:ignore.self
          >Crie sua barbearia</a>
        </li>
        <li role="presentation">
          <a href="#pills-contact7"
             class="block rounded bg-neutral-100 px-4 pb-2 pt-2 text-xs font-medium uppercase text-neutral-500 data-[te-nav-active]:!bg-neutral-800 data-[te-nav-active]:text-neutral-50 dark:bg-neutral-700 dark:text-white dark:data-[te-nav-active]:!bg-neutral-900 dark:data-[te-nav-active]:text-neutral-50 md:mr-4"
             id="pills-contact-tab7"
             data-te-toggle="pill"
             @if($tab === 'pills-contact7') data-te-nav-active @endif
             data-te-target="#pills-contact7"
             role="tab"
             wire:click="selecionarTab('pills-contact7')"
             aria-controls="pills-contact7"
             aria-selected="false"
             wire:ignore.self
          >Suas Barbearias</a>
        </li>
        <li role="presentation">
          <a href="#pills-contact8"
             class="block rounded bg-neutral-100 px-4 pb-2 pt-2 text-xs font-medium uppercase text-neutral-500 data-[te-nav-active]:!bg-neutral-800 data-[te-nav-active]:text-neutral-50 dark:bg-neutral-700 dark:text-white dark:data-[te-nav-active]:!bg-neutral-900 dark:data-[te-nav-active]:text-neutral-50 md:mr-4"
             id="pills-contact-tab8"
             data-te-toggle="pill"
             @if($tab === 'pills-contact8') data-te-nav-active @endif
             data-te-target="#pills-contact8"
             role="tab"
             wire:click="selecionarTab('pills-contact8')"
             aria-controls="pills-contact8"
             aria-selected="false"
             wire:ignore.self
          >Agenda</a>
        </li>
      </ul>

      <!-- Logout button -->
      <form action="/logout" method="post" class="mt-2 lg:mt-0 lg:ml-4">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Sair</button>
      </form>
    </div>
  </div>
</nav>
<!--Tabs navigation-->

{{-- <ul
  class="mb-5 flex list-none flex-row flex-wrap border-b-0 pl-0"
  role="tablist"
  id="myTab"
  data-te-nav-ref>
  <li role="presentation" class="flex-auto text-center"
       wire:ignore.self
  >
    <a
      wire:ignore.self
      href="#tabs-home01"
      class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-black data-[te-nav-active]:text-black"
      data-te-toggle="pill"
      data-te-target="#tabs-home01"
      data-te-nav-active
      role="tab"
      aria-controls="tabs-home01"
      aria-selected="true"
      >Barbearias</a
    >
  </li>
  <li wire:ignore.self role="presentation" class="flex-auto text-center">
    <a
     wire:ignore.self
      href="#tabs-profile01"
      class="my-2  block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-black data-[te-nav-active]:text-black"
      data-te-toggle="pill"
      data-te-target="#tabs-profile01"
      role="tab"
      aria-controls="tabs-profile01"
      aria-selected="false"
      >Crie sua barbearia</a
    >
  </li>
  <li  class="flex-auto text-center">
    <a

      href="/meus-agendamentos"
      wire:navigate
      class="my-2  block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-black data-[te-nav-active]:text-black"





      >Agenda</a
    >
  </li>





   @if(auth()->user()->barbearias->count()>0)
   <li wire:ignore.self role="presentation" class="flex-auto text-center">
    <a
      wire:ignore.self
      href="#tabs-messages01"
      class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-black data-[te-nav-active]:text-black"
      data-te-toggle="pill"
      data-te-target="#tabs-messages01"
      role="tab"
      aria-controls="tabs-messages01"
      aria-selected="false"
      >Suas Barbearias</a
    >
  </li>
   @endif

</ul> --}}

<!-- TW Elements is free under AGPL, with commercial license required for specific uses. See more details: https://tw-elements.com/license/ and contact us for queries at tailwind@mdbootstrap.com -->


<!--Tabs content-->
<div class="mb-6">
  <div
    class="hidden @if($tab === 'pills-home7') opacity-100 @else opacity-0 @endif transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
    id="pills-home7"
    role="tabpanel"
    aria-labelledby="pills-home-tab7"

    @if($tab === 'pills-home7') data-te-tab-active @endif
    wire:ignore.self
    >

    @livewire('cliente.barbearias.barbearia-list')


</div>
  <div
    class="hidden @if($tab === 'pills-profile7') opacity-100 @else opacity-0 @endif  transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
    id="pills-profile7"
    role="tabpanel"
    aria-labelledby="pills-profile-tab7"
    wire:ignore.self
    @if($tab === 'pills-profile7') data-te-tab-active @endif
    >




  </div>
  <div
    class="hidden @if($tab === 'pills-contact7') opacity-100 @else opacity-0 @endif transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
    id="pills-contact7"
    role="tabpanel"
    aria-labelledby="tabs-profile-tab01"
    @if($tab === 'pills-contact7') data-te-tab-active @endif
    wire:ignore.self
    >
    <div class="grid  grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-5 ">
        @foreach(auth()->user()->barbeariasOwned as $barbearia)
        <div
        class="mx-auto mb-8 sm:mb-0  block rounded-lg max-w-[450px] bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
        <div
          class="  relative   overflow-hidden bg-cover bg-no-repeat"
          data-te-ripple-init
          data-te-ripple-color="light">
          <img
            class="rounded-t-lg  object-cover "
            src="/storage/{{ $barbearia->imagem }}"
           style="width: 450px; height: 317px;"
            alt="" />
          <a href="/{{$barbearia->slug}}"
            wire:navigate
            >
            <div
              class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-[hsla(0,0%,98%,0.15)] bg-fixed opacity-0 transition duration-300 ease-in-out hover:opacity-100"></div>
          </a>
        </div>
        <div class="p-6">
          <h5
            class="mb-2 text-xl font-medium leading-tight text-neutral-800">
          {{ $barbearia->nome }}
          </h5>
          <p class="mb-2 text-base text-neutral-600">
           Cep:   {{ $barbearia->cep }}


          </p>
          <p class="mb-2 text-base text-neutral-600">

            Rua:   {{ $barbearia->rua }}
           </p>
           <p class="mb-2 text-base text-neutral-600">

            Cidade:   {{ $barbearia->cidade }}
           </p>

           <p class="mb-4 text-base text-neutral-600">

            Estado:   {{ $barbearia->estado }}
           </p>
          <a
          type="button"
          data-te-ripple-init
          data-te-ripple-color="light"
          href="/gerenciar/{{$barbearia->slug}}"

          class="rounded bg-black px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-900 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-black active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]">
         Gerenciar Barbearia
        </a>
        </div>
        </div>
        @endforeach

    </div>
          </div>





  {{--   <div
    class="bg-neutral-100 p-12 text-center text-neutral-700">
    <h2 class="mb-4 text-4xl font-semibold">Barbearia</h2>
    <h4 class="mb-6 text-xl font-semibold">John Alisson</h4>
    <a
      type="button"
      data-te-ripple-init
      data-te-ripple-color="light"
      href="/agendar"
      wire:navigate
      class="rounded bg-black px-7 pb-2.5 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-900 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]">
       Agendar Agora
</a>
  </div> --}}
  <div
  class="hidden @if($tab === 'pills-contact8') opacity-100 @else opacity-0 @endif  transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
  id="pills-contact8"
  role="tabpanel"
  aria-labelledby="tabs-profile-tab01"
  @if($tab === 'pills-contact8') data-te-tab-active @endif
  wire:ignore.self
  >
       <livewire:cliente.agendamentos.agendamentos >
  </div>


 <script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";







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
