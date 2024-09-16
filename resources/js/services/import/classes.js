import { normalize } from "@amcharts/amcharts5/.internal/core/util/Animation";
import { xlsxToJson } from "../../utils/xlsx";
import { api } from "../api";

export async function importClasses(teachers, schoolId) {
    const groups = await xlsxToJson(['nome_da_turma', 'professor_responsavel', 'turno', 'ano']);
    const shifts = {
        manha: 'morning',
        tarde: 'afternoon',
        noite: 'night'
    };

    await Promise.all(
        groups.map(async (data) => {
            const { id: teacherId } = teachers.find(({ name }) => normalize(name) === normalize(data.professor_responsavel));
            const shift = data.turno.normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/\s+/g, '_')
                .toLowerCase();

            await api.post('/ClassSchoolCreate', {
                name: data.nome_da_turma,
                shift: shifts[shift],
                year: data.ano,
                school_id: schoolId,
                teacher_id: teacherId
            });
        })
    );
}
