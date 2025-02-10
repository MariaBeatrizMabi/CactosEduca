<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiteracyParameterValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('literacy_parameter_values')->insert([
            [
                'value' => 'with_support',
                'name_to_show' => 'Com apoio',
                'literacy_parameter_id' => 1
            ],
            [
                'value' => 'without_support',
                'name_to_show' => 'Sem apoio',
                'literacy_parameter_id' => 1
            ],
            [
                'value' => 'not_write',
                'name_to_show' => 'Não escreve',
                'literacy_parameter_id' => 1
            ],
        ]);

        DB::table('literacy_parameter_values')->insert([
            [
                'value' => 'recognizes_all_letters_worked_in_bimester',
                'name_to_show' => 'Reconhece todas as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
            [
                'value' => 'partially_recognizes_worked_letters_in_bimester',
                'name_to_show' => 'Reconhece parcialmente as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
            [
                'value' => 'write_all_letters_worked_in_bimester',
                'name_to_show' => 'Escreve todas as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
            [
                'value' => 'partially_write_worked_letters_in_bimester',
                'name_to_show' => 'Escreve parcialmente as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
            [
                'value' => 'not_recognize_written_letters_in_bimester',
                'name_to_show' => 'Não reconhece as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
            [
                'value' => 'not_write_worked_letters',
                'name_to_show' => 'Não escreve as letras trabalhadas no bimestre',
                'literacy_parameter_id' => 2
            ],
        ]);

        DB::table('literacy_parameter_values')->insert([
            [
                'value' => 'recognizes_all_vocal_encounters_worked_in_bimester',
                'name_to_show' => 'Reconhece todos os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
            [
                'value' => 'partially_recognizes_worked_vocal_encounters_in_bimester',
                'name_to_show' => 'Reconhece parcialmente os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
            [
                'value' => 'write_all_vocal_encounters_worked_in_bimester',
                'name_to_show' => 'Escreve todos os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
            [
                'value' => 'partially_write_worked_vocal_encounters_in_bimester',
                'name_to_show' => 'Escreve parcialmente os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
            [
                'value' => 'not_recognize_written_vocal_encounters_in_bimester',
                'name_to_show' => 'Não reconhece os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
            [
                'value' => 'not_write_worked_vocal_encounters_in_bimester',
                'name_to_show' => 'Não escreve os Encontros Vocálicos trabalhados no bimestre',
                'literacy_parameter_id' => 3
            ],
        ]);

        DB::table('literacy_parameter_values')->insert([
            [
                'value' => 'recognizes_all_syllable_family_worked_in_bimester',
                'name_to_show' => 'Reconhece as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
            [
                'value' => 'partially_recognizes_worked_syllable_family_in_bimester',
                'name_to_show' => 'Reconhece parcialmente as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
            [
                'value' => 'write_all_syllable_family_worked_in_bimester',
                'name_to_show' => 'Escreve as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
            [
                'value' => 'partially_write_worked_syllable_family_in_bimester',
                'name_to_show' => 'Escreve parcialmente as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
            [
                'value' => 'not_recognize_written_syllable_family_in_bimester',
                'name_to_show' => 'Não reconhece as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
            [
                'value' => 'not_write_worked_syllable_family_in_bimester',
                'name_to_show' => 'Não escreve as famílias silábicas trabalhadas no bimestre',
                'literacy_parameter_id' => 4
            ],
        ]);

        DB::table('literacy_parameter_values')->insert([
            [
                'value' => 'recognizes_all_numbers_worked_in_bimester',
                'name_to_show' => 'Reconhece os números trabalhadas no bimestre',
                'literacy_parameter_id' => 5
            ],
            [
                'value' => 'partially_recognizes_worked_numbers_in_bimester',
                'name_to_show' => 'Reconhece parcialmente os números trabalhadas no bimestre',
                'literacy_parameter_id' => 5
            ],
            [
                'value' => 'write_all_numbers_worked_in_bimester',
                'name_to_show' => 'Escreve os números trabalhadas no bimestre',
                'literacy_parameter_id' => 5
            ],
            [
                'value' => 'partially_write_worked_numbers_in_bimester',
                'name_to_show' => 'Escreve parcialmente os números trabalhadas no bimestre',
                'literacy_parameter_id' => 5
            ],
            [
                'value' => 'not_recognize_written_numbers_in_bimester',
                'name_to_show' => 'Não reconhece os números trabalhadas no bimestre',
                'literacy_parameter_id' => 5
            ],
            [
                'value' => 'not_write_worked_numbers_in_bimester',
                'name_to_show' => 'Não escreve',
                'literacy_parameter_id' => 5
            ],
        ]);
    }
}
