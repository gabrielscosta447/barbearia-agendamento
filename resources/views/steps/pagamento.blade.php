
@php
 $accessToken = env("MERCADO_PAGO_ACCESS_TOKEN");


 \MercadoPago\MercadoPagoConfig::setAccessToken($accessToken);

$cards = auth()->user()->getMercadoPagoCards();
foreach($cards as $card) {
    $cardIds[] = $card->id;
}

@endphp


  <div id="paymentBrick_container" wire:ignore x-data="mercadoPagoIntegration" x-init="initMercadoPago()">

</div>




@script
<script>

    Alpine.data('mercadoPagoIntegration', () => ({
        selectedPlan: '',
        initMercadoPago() {
            const mp = new MercadoPago('APP_USR-6c284546-a6b3-429f-8181-2a69a2c4f764', {
                locale: 'pt-br'
            });

            const bricksBuilder = mp.bricks();

            bricksBuilder.create("payment", "paymentBrick_container", {
                initialization: {
                    amount: 15,
/*   preferenceId: "1642165427-578edade-19d7-4033-a68d-52846af975ab", */
                    payer: {



        customerId: @json(auth()->user()->payer_id),
           cardsIds:  @json($cardIds),


                        firstName: "",
                        lastName: "",
                        email: @json(auth()->user()->email),
                    },
                },
                customization: {
                    visual: {
                        style: {
                            theme: "default",
                        },
                    },
                    paymentMethods: {

                        creditCard: "all",
                        debitCard: "all",
                   
                        bankTransfer: "all",
                        atm: "all",
             /*      mercadoPago: "all", */

                        maxInstallments: 1
                    },
                },
                callbacks: {
                    onReady: () => {
                        // Callback chamado quando o Brick está pronto.
                        // Aqui você pode omitir carregamentos do seu site, por exemplo.
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {

                     @this.save(

                      formData,
                         selectedPaymentMethod,
                        this.selectedPlan


                     );
                    },
                    onError: (error) => {
                        // Callback chamado para todos os casos de erro relacionados ao Brick.
                        console.error(error);
                    },
                },
            });
        },
    }));
</script>
@endscript
 {{--  <div id="paymentBrick_container" x-data="{ initialized: false }" ></div>

<script>
    // Função para inicializar o MercadoPago e renderizar o tijolo de pagamento
    async function initMercadoPago() {
        const mp = new MercadoPago('APP_USR-6c284546-a6b3-429f-8181-2a69a2c4f764', {
            locale: 'pt-br'
        });
        const bricksBuilder = mp.bricks();

        try {
            const settings = {
                initialization: {
                    amount: 15,
                    payer: {
                        firstName: "",
                        lastName: "",
                        email: "test_user_1498281909@testuser.com",
                    },
                },
                customization: {
                    visual: {
                        style: {
                            theme: "default",
                        },
                    },
                    paymentMethods: {
                        mercadoPago: "all",
                        creditCard: "all",
                        debitCard: "all",
                        ticket: "all",
                        bankTransfer: "all",
                        atm: "all",
                        onboarding_credits: "all",
                        wallet_purchase: "all",
                        ticket: "all",
                        maxInstallments: 1
                    },
                },
                callbacks: {
                    onReady: () => {
                        // Callback called when Brick is ready.
                        // Here, you may omit loadings from your website, for instance.
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {
                        // Callback called when the user submits the form
                        // You may want to handle form submission here
                        // Example: @this.save(formData, selectedPaymentMethod);
                    },
                    onError: (error) => {
                        // callback called to all error cases related to the Brick
                        console.error(error);
                    },
                },
            };
            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
            );

            // Defina o valor de initialized como true para indicar que o MercadoPago foi inicializado
            document.getElementById("paymentBrick_container").dataset.initialized = true;
        } catch (error) {
            console.error("Error rendering payment brick:", error);
        }
    }

    // Chame a função initMercadoPago se o elemento paymentBrick_container ainda não foi inicializado

        const initialized = document.getElementById("paymentBrick_container").dataset.initialized;
        if (!initialized) {
            initMercadoPago();
        }
        document.getElementById("paymentBrick_container").addEventListener("", function() {
          location.reload();
    });

</script>
 --}}
