


<div>

      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
          <!-- card1 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Ganhos de Hoje</p>
                      <h5 class="mb-2 font-bold dark:text-white">R${{ $this->totalhoje }}</h5>


                      <p class="mb-0 dark:text-white dark:opacity-60">

                        @if($this->diferencapercentual > 0)
                        <span class="text-sm font-bold leading-normal text-emerald-500">{{ $this->diferencapercentual }}%</span>
                        @else
                        <span class="text-sm font-bold leading-normal text-red-500">{{ $this->diferencapercentual }}%</span>
                        @endif

                        desde ontem
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                      <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card2 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Usuários de Hoje</p>
                      <h5 class="mb-2 font-bold dark:text-white">{{$this->usersToday}}</h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        @if($this->usersPorcentagem > 0)
                        <span class="text-sm font-bold leading-normal text-emerald-500">{{$this->usersPorcentagem}}%</span>
                        @else
                        <span class="text-sm font-bold leading-normal text-red-500">{{$this->usersPorcentagem}}%</span>
                        @endif
                        desde a última semana
                      </p>
                    </div>
                  </div>

                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                      <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <!-- card3 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Novos Clientes</p>
                      <h5 class="mb-2 font-bold dark:text-white">{{ $this->usersLastQuarterComparison['usuarios_ultimo_trimestre_atual'] }}</h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        @if( $this->usersLastQuarterComparison['porcentagem_aumento'] < 0)
                        <span class="text-sm font-bold leading-normal text-red-600">{{ $this->usersLastQuarterComparison['porcentagem_aumento'] }}</span>
                        @else
                        <span class="text-sm font-bold leading-normal text-emerald-500">{{ $this->usersLastQuarterComparison['porcentagem_aumento'] }}%</span>
                        @endif
                       desde o último trimestre
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                      <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card4 -->
          <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total do Mês</p>
                      <h5 class="mb-2 font-bold dark:text-white">R${{ $this->totalMes['total_mes_atual'] }}</h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        @if( $this->totalMes['diferenca'] < 0)
                        <span class="text-sm font-bold leading-normal text-red-600">{{ $this->totalMes['diferenca'] }}</span>
                        @else
                        <span class="text-sm font-bold leading-normal text-emerald-500">{{ $this->totalMes['diferenca'] }}%</span>
                        @endif
                        desde o último mês
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
                      <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 2 -->

        <div class="flex flex-col justify-center mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none mb-6">
            <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                <h6 class="capitalize dark:text-white">Quantidade de Agendamentos</h6>
                <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                  <i class="fa fa-arrow-up text-emerald-500"></i>
            
                </p>
              </div>
              <div class="flex-auto p-4">
                <div>
                  <canvas id="chart-line" height="300"></canvas>

                </div>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                <h6 class="capitalize dark:text-white">Vendas</h6>
                <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                  <i class="fa fa-arrow-up text-emerald-500"></i>
            
                </p>
              </div>
              <div class="flex-auto p-4">
                <div>
                  <canvas id="chart-sales" height="300"></canvas>

                </div>
              </div>
            </div>
          </div>









        <!-- cards row 3 -->

        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 rounded-t-4">
                <div class="flex justify-between">
                  <h6 class="mb-2 dark:text-white">Agendamentos por Serviço</h6>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="items-center w-full mb-4 align-top border-collapse border-gray-200 dark:border-white/40">
                  <tbody>
                    @foreach($this->barbearia->barbeiros()->withTrashed()->get() as $barbeiro)
                    @foreach($barbeiro->cortes as $corte)
                    @if(!$loop->last)
                    <tr>
                      <td class="p-2 align-middle bg-transparent border-b w-3/10 whitespace-nowrap dark:border-white/40">
                        <div class="flex items-center px-2 py-1">
                          <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" />
                            </svg>

                          </div>
                          <div class="ml-6">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Serviço:</p>
                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $corte->corte->nome }}</h6>
                          </div>
                        </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                        <div class="text-center">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Quantidade:</p>
                          <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $corte->corte->agendamentos()->onlyTrashed()->count() }}</h6>
                        </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                        <div class="text-center">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Vendas:</p>
                          <h6 class="mb-0 text-sm leading-normal dark:text-white">R${{ $corte->corte->agendamentos()->onlyTrashed()->count() * $corte->corte->preco }}</h6>
                        </div>
                      </td>
                     
                    </tr>
                    @else
                    <tr>
                      <td class="p-2 align-middle bg-transparent  w-3/10 whitespace-nowrap dark:border-white/40">
                        <div class="flex items-center px-2 py-1">
                          <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" />
                            </svg>

                          </div>
                          <div class="ml-6">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Serviço:</p>
                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $corte->corte->nome }}</h6>
                          </div>
                        </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent  whitespace-nowrap dark:border-white/40">
                        <div class="text-center">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Quantidade:</p>
                          <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $corte->corte->agendamentos()->onlyTrashed()->count() }}</h6>
                        </div>
                      </td>
                      <td class="p-2 align-middle bg-transparent  whitespace-nowrap dark:border-white/40">
                        <div class="text-center">
                          <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">Vendas:</p>
                          <h6 class="mb-0 text-sm leading-normal dark:text-white">R${{ $corte->corte->agendamentos()->onlyTrashed()->count() * $corte->corte->preco }}</h6>
                        </div>
                      </td>

                    </tr>

                    @endif
                    @endforeach
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
          </div>

            </div>
          </div>
        </div>


</div>
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
