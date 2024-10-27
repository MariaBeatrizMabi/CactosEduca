<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterventionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('interventions')->insert([
            ['code' => 'INT-1', 'description' => 'Escrita espontânea', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-2', 'description' => 'Socialização das produções escritas', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-3', 'description' => 'Trabalhar o nome próprio dos colegas', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-4', 'description' => 'Jogos: confronto entre figura / letra inicial, letra inicial / palavra e figura / palavra', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-5', 'description' => 'Análise fonológica (sons) e linguística (letra) das palavras', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-6', 'description' => 'Contato com farto e variado material escrito', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-7', 'description' => 'Situações reais de comunicação (função social da escrita)', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-8', 'description' => 'Trabalhar com os textos conhecidos de memória pelos alunos...', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-9', 'description' => 'Propor que os alunos escrevam e interpretem seus escritos...', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-10', 'description' => 'Organizar agrupamentos heterogêneos produtivos', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-11', 'description' => 'Esclarecer as diferentes funções do trabalho em dupla...', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-12', 'description' => 'Ajustar o nível das intervenções às possibilidades do aluno...', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-13', 'description' => 'Textos: produção coletiva; leitura e interação...', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-14', 'description' => 'Situações reais de comunicação', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-15', 'description' => 'Atividades de leitura e escrita diárias', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-16', 'description' => 'Análise linguística (constituição da palavra)', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-17', 'description' => 'Palavras conhecidas', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-18', 'description' => 'Nome do aluno', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-19', 'description' => 'Reconhecimento dos sons das letras isoladas...', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-20', 'description' => 'Trabalhar com rimas', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-21', 'description' => 'Trabalhar com o traçado das letras', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-22', 'description' => 'Estimular a revisão das produções pelos próprios alunos...', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-23', 'description' => 'Ler os textos mesmo quando ainda não se sabe ler...', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-24', 'description' => 'Agrupar os alunos, conforme o objetivo da atividade...', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-25', 'description' => 'Propiciar a evolução pela sua atuação significativa...', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-26', 'description' => 'Jogos, cruzadinhas, remontar textos por meio de tiras...', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-27', 'description' => 'Leitura e produção de textos diversos', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-28', 'description' => 'Contar o número de palavras em uma frase', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-29', 'description' => 'Montar um texto a partir de palavras', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-30', 'description' => 'Montar palavras com o alfabeto móvel', 'evaluation_type' => 'alphabetical_syllabic'],
            ['code' => 'INT-31', 'description' => 'Análise de diferentes gêneros textuais', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-32', 'description' => 'Percepção da relação do que se fala com o que se escreve...', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-33', 'description' => 'Análise linguística, enfatizando a morfologia e ortografia...', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-34', 'description' => 'Ler seus próprios escritos', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-35', 'description' => 'Revisão e reescrita dos textos de modo coletivo...', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-36', 'description' => 'Propor situações que façam com que os alunos ponham em jogo...', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-37', 'description' => 'Criar ambiente rico de materiais de leitura e escrita', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-38', 'description' => 'Atribuir à escrita a função de informação e registro', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-39', 'description' => 'Trabalhar com o texto como o "lugar" do aprender a ler e escrever...', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-40', 'description' => 'Confronto das escritas do aluno com a de seus colegas...', 'evaluation_type' => 'alphabetical'],
        ]);
    }
}
