import { jsonToXlsx } from "../../utils/xlsx";

export const exportSchoolsData = (data) => jsonToXlsx({
    name: 'Escolas',
    data: data.map((row) => ({
        Nome: row.name,
        Múnicipio: row.city_name,
        Localidade: row.location_id,
        'Código de acesso': row.acess_cod,
    })),
});

export const exportSchoolsSampleData = () => jsonToXlsx({
    name: 'Planilha Modelo - Escolas',
    data: [{
        'Nome': '',
        'Múnicipio': '',
        'Localidade': '',
        'Código de acesso': '',
        'Senha de Acesso': ''
    }],
});
