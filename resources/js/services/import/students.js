import moment from "moment";
import { normalize } from "../../utils/normalize";
import { xlsxToJson } from "../../utils/xlsx";
import { api } from "../api";

export async function importStudents(school) {
    const students = await xlsxToJson([
        'nome_do_aluno',
        'data_de_nascimento',
        'matricula',
        'sexo',
        'data_da_matricula',
        'pcd'
    ]);
    const genders = {
        masculino: 'male',
        feminino: 'female'
    };
    const pcdLabel = {
        sim: true,
        nao: false
    }

    await Promise.all(
        students.map(async (data) => {
            await api.post('/api/students', {
                name: data.nome_do_aluno,
                date_of_birth: moment(data.data_de_nascimento, "DD/MM/YYYY").toISOString().slice(0, 10),
                gender: genders[normalize(data.sexo)],
                enrollment: data.matricula,
                enrollment_date: moment(data.data_da_matricula, "DD/MM/YYYY").toISOString().slice(0, 10),
                people_with_disabilities: pcdLabel[normalize(data.pcd)],
                school_id: school
            });
        })
    );
}

export async function importClassStudents(students, classId) {
    const studentsData = await xlsxToJson([
        'nome_do_aluno'
    ]);

    await Promise.all(
        studentsData.map(async ({ nome_do_aluno }) => {
            const studentId = students.find(({ name }) => name === nome_do_aluno)?.id;
            await api.post(`/api/classes/${classId}/students/${studentId}`);
        })
    );
}
