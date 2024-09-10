import { jsonToXlsx } from "../../utils/xlsx";

export const exportStudentsData = (data) => jsonToXlsx({
    name: 'Alunos',
    data: data.map((data) => ({
        'Nome do aluno': data.name,
        'Sexo': data.gender === 'male' ? 'Masculino' : 'Feminino',
        'Data de Nascimento': (new Date(data.date_of_birth)).toLocaleDateString('pt-BR'),
        'Matrícula': data.enrollment,
        'Matriculado em': (new Date(data.enrollment_date)).toLocaleDateString('pt-BR'),
        'PCD': data.people_with_disabilities ? 'Sim' : 'Não'
    })),
});

export const exportStudentsSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Alunos',
    data: [{
        'Nome do aluno': '',
        'Sexo': '',
        'Data de Nascimento': '',
        'Matrícula': '',
        'Data da matricula': '',
        'PCD': ''
    }],
});
