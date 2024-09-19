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

    const normalizeText = text => text.trim().toLowerCase();

    await Promise.all(
        groups.map(async (data) => {
            const normalizedProfessorName = normalizeText(data.professor_responsavel);
            console.log('Normalized professor name:', normalizedProfessorName);

            const teacher = teachers.find(({ name }) => normalizeText(name) === normalizedProfessorName);

            if (!teacher) {
                console.error(`Professor não encontrado para o nome: ${data.professor_responsavel}`);
                return;
            }

            const { id: teacherId } = teacher;

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