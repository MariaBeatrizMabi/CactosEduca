import { READING_LEVEL } from "../../enums/readingLevel";
import { WRITING_LEVEL } from "../../enums/writingLevel";
import { jsonToXlsx } from "../../utils/xlsx";

export const exportExamsData = (data) => jsonToXlsx({
    name: 'Sondagens',
    data: data.map((row) => ({
        'Nível de Leitura': READING_LEVEL[row.reading],
        'Nível de Escrita': WRITING_LEVEL[row.writing],
        'Ações de Intervenção': row.action
    })),
});

export const exportExamsSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Sondagens',
    data: [{
        'Nível de Leitura': '',
        'Nível de Escrita': '',
        'Ações de Intervenção': ''
    }],
});
