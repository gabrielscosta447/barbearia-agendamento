<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


class General extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.general';
        public $response;
        public $resposta;
        public $cep;
    /*
     * Initialize step fields
     */
  public function mount() {
    $this->mergeState([
       'cep' => '',
    ]);
  }

     public function updatedStateCep($name, $value)
     {
         $this->response = Http::get("https://viacep.com.br/ws/{$name}/json/");


      
     
         if (!$this->response || isset($this->response['erro'])) {
             return false;
         }
     
         $this->mergeState([
            
             'bairro'  => $this->response['bairro'] ?? null,
             'rua'     => $this->response['logradouro'] ?? null ,
             'cidade'  => $this->response['localidade'] ?? null ,
             'estado'  => $this->response['uf'] ?? null,
         
         ]);

     
     }

  

  
    /*
    * Step icon 
    */
    public function icon(): string
    {
        return 'scissors';
    }

  

    /*
     * Step Validation
     */
    public function validate()
    {
        return [
            [
                'state.cpf' => ['required', 'string', 'unique:barbearias,cpf'], 
                'state.name' => ['required', 'string'],
                'state.bairro' => ['required', 'string'],
                'state.cidade' => ['required', 'string'],
                'state.estado' => ['required', 'string'],
                'state.complemento' => ['required', 'string'],
                'state.rua' => ['required', 'string'],
                'state.slug' => ['required', 'string', 'unique:barbearias,slug'],
                'state.cep' => ['required', 'string'],
                'state.tipo_chave' => ['required', 'string'],
                'state.chave_pix' => ['required', 'string'],
            
            ],
            [],
            [
                'state.cpf' => __("CPF"),
                'state.name' => __('Nome'),
                'state.bairro' => __('Bairro'),
                'state.estado' => __('Estado'),
                'state.complemento' => __('Complemento'),
                'state.rua' => __('Rua'),
                'state.slug' => __('URL'),
                'state.cidade' => __('Cidade'),
                'state.cep' => __('CEP'),
                'state.tipo_chave' => __('Tipo de Chave Pix'),
                'state.chave_pix' => __('Chave Pix')
            ],
        ];
    }
    

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Geral');
    }
} 