import { xlsxToJson } from "../../utils/xlsx";
import { api } from "../api";
import {normalize} from "../../utils/normalize.js";

export async function importSchools(cities, locations) {
    const teachers = await xlsxToJson([
        'nome',
        'municipio',
        'localidade',
        'codigo_de_acesso',
        'senha_de_acesso'
    ]);

    for (const row of teachers) {
        const cityExists = Array.isArray(cities) && cities.find(({ name }) => normalize(name) === normalize(row.municipio));
        const locationExists = Array.isArray(locations) && locations.find(({ name }) => normalize(name) === normalize(row.localidade));

        if (!cityExists) {
            const { data } = await api.post('/api/cities', { name: row.municipio });
            cities.push(data);
        }

        if (!locationExists) {
            const { data } = await api.post('/api/locations', { name: row.localidade });
            locations.push(data);
        }
    }
    await Promise.all(
        teachers.map(async ({
            nome,
            municipio,
            localidade,
            codigo_de_acesso,
            senha_de_acesso
        }) => {
            const city = cities?.find(({ name }) => normalize(name) === normalize(municipio));
            const location = locations?.find(({ name }) => normalize(name) === normalize(localidade));

            await api.post('/ManagementSchoolCreate', {
                name: nome,
                location_id: location?.id,
                city_id: city?.id,
                acess_cod: codigo_de_acesso,
                password: senha_de_acesso,
                type: 'school',
            });
        })
    );
}
