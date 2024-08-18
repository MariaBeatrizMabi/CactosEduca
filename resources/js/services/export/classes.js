import { jsonToXlsx } from "../../utils/xlsx";

export const exportClassesData = (data) => jsonToXlsx({
    name: 'Turmas',
    data: data.map((row) => ({
        'Turma': row.name,
        'Professor reponsável': row.teacher_name
    })),
});

export const exportClassesSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Turmas',
    data: [{
        'Nome da Turma': '',
        'Professor Reponsável': '',
        'Turno': '',
        'Ano': ''
    }],
});

export const exportClassStudentsData = (data) => jsonToXlsx({
    name: 'Alunos turma',
    data: data.map((row) => ({
        'Aluno': row.studentName,
        'Turma': row.className
    }))
});

export const exportClassStudentsSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Alunos turma',
    data: [{
        'Nome do aluno': ''
    }],
});
