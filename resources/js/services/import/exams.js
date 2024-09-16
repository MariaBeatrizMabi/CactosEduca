import { READING_LEVEL } from "../../enums/readingLevel";
import { WRITING_LEVEL } from "../../enums/writingLevel";
import { normalize } from "../../utils/normalize";
import { xlsxToJson } from "../../utils/xlsx";
import { api } from "../api";

export async function importExams(studentId, classId) {
    const exams = await xlsxToJson(['nivel_de_leitura', 'nivel_de_escrita', 'acoes_de_intervencao']);

    await Promise.all(
        exams.map(async (data) => {
            const [readingLevel] = Object.entries(READING_LEVEL).find(([_, value]) => {
                return normalize(value) === normalize(data.nivel_de_leitura)
            });
            const [writingLevel] = Object.entries(WRITING_LEVEL).find(([_, value]) => {
                return normalize(value) === normalize(data.nivel_de_escrita)
            });

            await api.post('/api/exams', {
                reading: readingLevel,
                writing: writingLevel,
                action: data.acoes_de_intervencao,
                student_id: studentId,
                class_id: classId
            });
        })
    );
}
