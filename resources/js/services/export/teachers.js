import { jsonToXlsx } from "../../utils/xlsx";

export const exportTeachersData = (data) => jsonToXlsx({
    name: 'Professores',
    data: data.map((data) => ({
        'Professor': data.name,
        'Acesso': data.acess_cod
    })),
});

export const exportTeachersSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Professores',
    data: [{
        'Professor': '',
        'Acesso': '',
        'Senha': ''
    }],
});
