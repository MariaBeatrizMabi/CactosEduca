import { xlsxToJson } from "../../utils/xlsx";
import { api } from "../api";

export async function importTeachers(school) {
    const teachers = await xlsxToJson(['professor', 'acesso', 'senha']);
    await Promise.all(
        teachers.map(async ({ professor, acesso, senha }) => {
            await api.post('/TeacherCreate', {
                name: professor,
                acess_cod: acesso,
                password: senha,
                school_id: school
            });
        })
    );
}
