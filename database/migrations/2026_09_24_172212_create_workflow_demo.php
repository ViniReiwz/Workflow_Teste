<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Uspdev\Forms\Models\FormDefinition;
use Uspdev\Forms\Services\FormDefinitionService;
use Uspdev\Workflow\Models\WorkflowDefinition;



class CreateWorkflowDemo extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $workflow_demo = Request::create('/workflow/demo', 'POST', [
            'name' => 'emprestimo_livro_simples',
            'description' => 'Workflow de exemplo para nova lógica, no contexto de biblioteca',
            'definition' => '{ "name": "emprestimo_livro_simples", "label": "Empréstimo de Livro", "initial_places": ["pedido"], "roles": [ {"name": "usuario", "label": "Usuário", "source": "*Alunogr"}, {"name": "bibliotecario", "label": "Bibliotecário"} ], "places": [ {"name": "pedido", "label": "Enviar pedido", "roles": ["usuario"]}, {"name": "analise", "label": "Em análise", "roles": ["bibliotecario"]}, {"name": "finalizado", "label": "Finalizado", "roles": ["usuario"]} ], "transitions": [ { "name": "tr_enviar", "label": "Enviar para análise", "from": "pedido", "tos": ["analise"] }, { "name": "tr_aprovar", "label": "Aprovar pedido de empréstimo", "from": "analise", "tos": ["finalizado"],"form": false, "notifications": { "append_roles": ["bibliotecario"] } }, {"name": "tr_rejeitar","label":"Solicitar correção","from":"analise","tos":["pedido"],"form":"rejeitar_pedido","bindings":[{"attribute":"bibliotecario","from":"form.user_codpes","resolver":"user_by_codpes"}],"notifications":{"append_roles":["usuario"]}} ] }',
        ]);

       $form_demo =  Request::create('/workflow/formdemo', 'POST', [
            'name' => 'rejeitar_pedido',
            'version' => 1,
            'group' => 'workflow',
            'description' => 'Formulário de rejeição de pedido de empréstimo de livro',
            'fields' => '[ { "name": "user_codpes", "type": "text", "label": "Código do Bibliotecário", "required": true, "validation_rule": "max:8" }, [ { "name": "justificativa", "type": "textarea", "label": "Justificativa da rejeição", "required": true, "validation_rule": "min:20", "width": 8 }, { "name": "data_parecer", "type": "date", "label": "Data do parecer", "required": true } ] ]'
        ]);

        $workflow_def = WorkflowDefinition::storeDefinition($workflow_demo);
        app(FormDefinitionService::class)->createFromRequest($form_demo);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $workflow_def = WorkflowDefinition::where('name', 'emprestimo_livro_simples')->first();
        $workflow_def->delete();

        $form_demo = FormDefinition::where('name', 'rejeitar_pedido')->first();
        $form_demo->delete();
    }
};
