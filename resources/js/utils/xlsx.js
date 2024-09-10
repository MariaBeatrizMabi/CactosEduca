import { normalize } from './normalize';
import XLSX from 'xlsx';

export function jsonToXlsx({ data, name }) {
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet);
    XLSX.writeFile(workbook, `${name}.xlsx`, { compression: true });
}

export async function xlsxToJson(expectedKeys) {
    const [fileHandle] = await showOpenFilePicker({
        startIn: 'documents',
        multiple: false,
        types: [
            {
                description: 'Excel',
                accept: {
                    "application/*": ['.xlsx']
                }
            }
        ]
    });

    const file = await fileHandle.getFile()
    const reader = new FileReader();
    reader.readAsArrayBuffer(file);

    return new Promise((resolve, reject) => {
        reader.onload = (e) => {
            const workbook = XLSX.read(e.target.result, { type: 'binary' });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const data = XLSX.utils.sheet_to_json(sheet, { raw: false, dateNF: 'dd/mm/yyyy' });


            const keys = Object.keys(data[0]).map((key) => normalize(key));
            console.log(expectedKeys);
            console.log(keys);
            if (!expectedKeys.every((item) => keys.includes(item))) {
                reject('Planilha inválida.');
            }

            resolve(data.map((row) => {
                return Object.fromEntries(
                    Object.keys(row).map((key) => [normalize(key), row[key]])
                );
            }));
        }
    });
}
