<script setup>
import { ref, onMounted, defineEmits, computed, toRaw, watch } from "vue";
import axios from "axios";
import { api } from "../services/api";
import MenuComponent from "../components/menu.vue";
import TitleComponent from "../components/title.vue";
import userWelcomeComponent from "../components/userWelcome.vue";
import TableComponent from "../components/table.vue";
import TableComponentComponent from "../components/tableComponent.vue";

import ModalComponent from "../components/modal.vue";
import InputComponent from "../components/input.vue";
import InputComponentPassword from "../components/inputPassword.vue";
import LoadingComponent from "../components/loading.vue";
import ModalComponentDeleted from "../components/modalComponentShort.vue";
import SelectComponent from "../components/select.vue";
import NewSelectComponent from "../components/SelectComponent.vue";
import Breadcrumb from "../components/Breadcrumb";
import {
    exportStudentsData,
    exportStudentsSampleData,
    exportTeachersData,
    exportTeachersSampleData,
    exportClassesData,
    exportClassesSampleData,
} from '../services/export';
import {
    importStudents,
    importClasses,
    importTeachers
} from '../services/import';

const emit = defineEmits(["viewDetails", "updateAction", "deletedAction"]);

const userType = ref("");
const userID = ref("");
const showModalCreation = ref(false);
const ShowModalClassCreation = ref(false);
const showModalStudentCreation = ref(false);
const isLoading = ref(false);

const showModalData = ref();
const showModalClassData = ref();
const showModalStudentData = ref();
const updateModalTeacherData = ref();
const updateModalClassData = ref();
const idToUpdate = ref(null);
const deletedModal = ref();
const deletedClassDataModal = ref();
const deletedStudentModal = ref();
const idToDeleted = ref(null);

const schoolId = ref();

let formDataTeachersPreview = ref({
    id: "",
    name: "",
    acess_cod: "",
    password: "",
});

const formDataTeacherAdd = ref({
    name: "",
    school_id: schoolId.value,
    group_id: "",
    acess_cod: "",
    password: "",
    type: "teacher",
});

const formDataStudentAdd = ref({
    name: "",
    gender: "",
    enrollment_date: "",
    enrollment: "",
    date_of_birth: "",
    people_with_disabilities: '',
    school_id: userID.value,
});

let formDataClassPreview = ref({
    id: "",
    name: "",
    acess_cod: "",
    password: "",
});

let formDataStudentPreview = ref({
    name: "",
    enrollment: "",
    school_id: userID.value,
});

const formDataStudentExport = ref({
    name: "",
    enrollment: "",
    school_id: userID.value,
});

const formDataClassAdd = ref({
    name: "",
    shift: "",
    school_id: userID.value,
    teacher_id: "",
});

let formDataVisualize = ref({
    id: "",
    name: "",
    acess_cod: "",
    password: "",
});

let formDataClassVisualize = ref({
    name: "",
    school_id: userID.value,
    teacher_id: "",
});

let formDataStudentVisualize = ref({
    name: "",
    school_id: userID.value,
    teacher_id: "",
});

let formDataUpdate = ref({
    id: "",
    name: "",
    acess_cod: "",
    password: "",
});

let formDataClassUpdated = ref({
    name: "",
    shift: "",
    school_id: userID.value,
    teacher_id: "",
});

let formDataDeleted = ref({
    name: "",
});

let formDataClassDeleted = ref({
    name: "",
});

let formDataStudentDeleted = ref({
    name: "",
});

const OpenModalTeachersCreation = () => {
    showModalCreation.value = true;
};

const OpenModalClassCreation = () => {
    ShowModalClassCreation.value = true;
};

const OpenModalStudentCreation = () => {
    showModalStudentCreation.value = true;
};

function resetForm() {
    formDataTeacherAdd.value = {
        name: "",
        school_id: schoolId.value,
        group_id: "",
        acess_cod: "",
        password: "",
        type: "teacher",
    };
}

function resetFormClass() {
    formDataClassAdd.value = {
        name: "",
        school_id: schoolId.value,
        teacher_id: "",
        shift: '',
        year: ''
    };
}

function resetFormStudent() {
    formDataStudentAdd.value = {
        name: "",
        gender: "",
        enrollment_date: "",
        enrollment: "",
        date_of_birth: "",
        people_with_disabilities: '',
        school_id: userID.value,
    };
}

async function getUserType() {
    const { data: loginUserData } = await axios.get("/loginUser");
    userType.value = loginUserData.type;
    userID.value = loginUserData.id;
}

async function getSchool() {
    const { data: school } = await api.get('/api/user/school');
    schoolId.value = school.id;
}

async function getTableTeacherData() {
    try {
        const response = await axios("/TeachersSchool");

        formDataTeachersPreview.value = response.data.map((take) => ({
            id: take.id,
            name: take.name,
            acess_cod: take.user.acess_cod,
        }));
    } catch (error) {
        console.error(error);
    }
}

async function getTableClassData() {
    try {
        const response = await axios.get("/ClassSchool");

        formDataClassPreview.value = response.data.map((take) => ({
            id: take.id,
            name: take.name,
            teacher_name: take.teacher ? take.teacher.name : "",
        }));
    } catch (error) {
        console.error(error);
    }
}

async function getTableStudentData() {
    try {
        const response = await axios("/StudentsData");

        formDataStudentPreview.value = response.data.map((take) => ({
            id: take.id,
            name: take.name,
            enrollment: take.enrollment,
        }));

        formDataStudentExport.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

async function submitForm() {
    try {
        isLoading.value = true;
        await axios.post("/TeacherCreate", {
            ...formDataTeacherAdd.value,
            school_id: schoolId.value,
        });

        getTableTeacherData();

        showModalCreation.value = false;

        resetForm();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
        isLoading.value = false;
    }
}

async function submitStudentForm() {
    try {
        isLoading.value = true;

        await api.post("/api/students", {
            ...formDataStudentAdd.value,
            people_with_disabilities: JSON.parse(formDataStudentAdd.value.people_with_disabilities),
            school_id: schoolId.value,
        });

        getTableStudentData();
        showModalStudentCreation.value = false;
        resetFormStudent();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
        isLoading.value = false;
    }
}

async function submitFormClass() {
    try {
        isLoading.value = true;
        await axios.post("/ClassSchoolCreate", {
            ...formDataClassAdd.value,
            school_id: schoolId.value,
        });

        getTableClassData();

        ShowModalClassCreation.value = false;

        resetFormClass();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
        isLoading.value = false;
    }
}

async function ShowSchoolTeachersData(id) {
    showModalData.value = true;

    try {
        const { data: teacher } = await api.get(`/api/teachers/${id}`);

        if (teacher) {
            formDataVisualize.value = {
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.user.acess_cod,
                type: "teacher",
            };
        } else {
            console.error(
                `Não foi possível encontrar o professor com o ID ${id}`
            );
        }
    } catch (error) {
        console.error(error);
    }
}

async function ShowSchoolClassData(id) {
    window.location.href = `/class/${id}`;
}

async function ShowStudentData(id) {
    window.location.href = `/student/${id}`;
}

async function UpdateSchoolTeachersData(id) {
    updateModalTeacherData.value = true;

    try {
        const { data: teacher } = await api.get(`/api/teachers/${id}`);

        idToUpdate.value = id;

        if (teacher) {
            formDataUpdate.value = {
                ...formDataUpdate,
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.user.acess_cod,
            };
        } else {
            console.error(
                `Não foi possível encontrar o professor com o ID ${id}`
            );
        }
    } catch (error) {
        console.error(error);
    }
}

async function UpdateSchoolClassData(id) {
    updateModalClassData.value = true;

    idToUpdate.value = id;

    const { data } = await api.get(`/api/classes/${id}`);
    formDataClassUpdated.value = {
        name: data.name,
        shift: data.shift,
        year: data.year,
        teacher_id: data.teacher_id,
    };
}

async function updateDataForm() {
    try {
        await api.put(
            `/api/teachers/${idToUpdate.value}`,
            formDataUpdate.value
        );

        updateModalTeacherData.value = false;
        isLoading.value = true;

        getTableTeacherData();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function updateClassDataForm() {
    const id = idToUpdate.value;
    try {
        const formData = {
            name: formDataClassUpdated.value.name,
            shift: formDataClassUpdated.value.shift,
            year: formDataClassUpdated.value.year,
            school_id: schoolId.value,
            teacher_id: formDataClassUpdated.value.teacher_id,
        };

        const response = await axios.put(`/ClassSchoolUpdate/${id}`, formData);
        updateModalClassData.value = false;
        isLoading.value = true;

        getTableClassData();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function deletedModalTeachersShow(id) {
    deletedModal.value = true;
    resetForm();

    try {
        const { data } = await api.get(`/api/teachers/${id}`);

        idToDeleted.value = id;

        formDataDeleted.value = {
            name: data.name,
        };
    } catch (error) {
        console.error("Erro ao buscar dados do professor:", error);
    }
}

async function deletedTeachers() {
    try {
        await api.delete(`/api/teachers/${idToDeleted.value}`);
        deletedModal.value = false;
        isLoading.value = true;

        getTableTeacherData();
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error);
    }
}

async function deletedModalClassShow(id) {
    deletedClassDataModal.value = true;
    resetForm();
    try {
        const { data } = await api.get(`/api/classes/${id}`);
        idToDeleted.value = data.id;

        formDataClassDeleted.value = {
            name: data.name,
        };
    } catch (error) {
        console.error("Erro ao buscar dados do professor:", error);
    }
}

async function deleteClassData() {
    const id = idToDeleted.value;

    try {
        await axios.delete(`/ClassSchoolDelete/${id}`);
        deletedClassDataModal.value = false;
        isLoading.value = true;

        getTableClassData();
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error);
    }
}

async function deletedModalStudentShow(id) {
    deletedStudentModal.value = true;
    resetForm();
    try {
        const response = await axios.get(`/StudentsData/${id}`);

        const Student = response.data.find((Student) => Student.id === id);
        idToDeleted.value = id;
        formDataStudentDeleted.value = {
            name: Student.name,
        };
    } catch (error) {
        console.error("Erro ao buscar dados do professor:", error);
    }
}

async function deleteStudentData() {
    const id = idToDeleted.value;

    try {
        await axios.delete(`/StudentDelete/${id}`);
        deletedStudentModal.value = false;
        isLoading.value = true;

        getTableStudentData();
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error);
    }
}

function closeModalDeleted() {
    deletedModal.value = false;
    deletedClassDataModal.value = false;
    deletedStudentModal.value = false;
    resetForm();
}

function closeModalAdd() {
    showModalCreation.value = false;
    ShowModalClassCreation.value = false;
    showModalStudentCreation.value = false;
    resetForm();
}

function closeModal() {
    showModalData.value = false;
    showModalClassData.value = false;
    showModalStudentData.value = false;
    resetForm();
}

function closeModalUpdated() {
    updateModalTeacherData.value = false;
    updateModalClassData.value = false;
    resetForm();
}

onMounted(async () => {
    isLoading.value = true;

    try {
        await getUserType();
    await getSchool();
    await getTableTeacherData();
    await getTableClassData();
    await getTableStudentData();
    isLoading.value = false;

    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error);
    }
    
});

async function handleImportTeachers() {
    await importTeachers(schoolId.value);
    await getTableTeacherData();
}

async function handleImportClasses() {
    await importClasses(toRaw(formDataTeachersPreview.value), schoolId.value);
    await getTableClassData();
}

async function handleImportStudents() {
    await importStudents(schoolId.value);
    await getTableStudentData();
}
</script>

<template>
    <LoadingComponent :isLoading="isLoading" />

    <ModalComponent
        v-if="showModalData"
        Titlevalue="Visualização de professores"
    >
        <div class="modal-body-size">
            <h2>Detalhes sobre o professor</h2>
            <div class="modal-content-details">
                <InputComponent
                    disabled="true"
                    labelTitle="Nome do professor"
                    placeholderValue="Nome da escola"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataVisualize.name"
                    @input="formDataVisualize.name = $event.target.value"
                />

                <InputComponent
                    disabled="true"
                    labelTitle="Código de acesso"
                    placeholderValue="Código de acesso"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataVisualize.acess_cod"
                    @input="formDataVisualize.acess_cod = $event.target.value"
                />
                <InputComponentPassword
                    disabled="true"
                    labelTitle="Senha de acesso"
                    placeholderValue="Senha"
                    icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                    :value="formDataVisualize.password"
                    @input="formDataVisualize.password = $event.target.value"
                />
            </div>
            <div class="modal-content-address"></div>
        </div>
        <div class="modal-end">
            <a
                style="margin-right: 5rem"
                class="close-modal"
                @click="closeModal()"
            >
                <svg
                    width="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <path
                        fill="red"
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                    ></path>
                </svg>
                Fechar
            </a>
        </div>
    </ModalComponent>

    <ModalComponent
        v-if="updateModalTeacherData"
        Titlevalue="Atualização de professores"
    >
        <div class="modal-body-size">
            <h2>Detalhes sobre o professor</h2>
            <div class="modal-content-details">
                <InputComponent
                    labelTitle="Nome do professor"
                    placeholderValue="Nome da escola"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataUpdate.name"
                    @input="formDataUpdate.name = $event.target.value"
                />
                <InputComponent
                    labelTitle="Código de acesso"
                    placeholderValue="Código de acesso"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataUpdate.acess_cod"
                    @input="formDataUpdate.acess_cod = $event.target.value"
                />
                <InputComponentPassword
                    labelTitle="Senha de acesso"
                    placeholderValue="Senha"
                    icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                    :value="formDataUpdate.password"
                    @input="formDataUpdate.password = $event.target.value"
                />
            </div>
            <div class="modal-content-address"></div>
        </div>
        <div class="modal-end">
            <a
                style="margin-right: 5rem"
                class="close-modal"
                @click="closeModalUpdated()"
            >
                <svg
                    width="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <path
                        fill="red"
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                    ></path>
                </svg>
                Fechar
            </a>
            <a class="school-add" @click="updateDataForm()">
                <svg
                    width="20"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <path
                        fill="var(--secondary-color)"
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                    />
                </svg>
                Atualizar Professores
            </a>
        </div>
    </ModalComponent>

    <div class="school-register">
        <ModalComponent
            v-if="showModalCreation"
            Titlevalue="Cadastro de professores"
        >
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Digite o nome completo do professor"
                        placeholderValue="Nome do professor"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataTeacherAdd.name"
                        typeValue="text"
                        @input="formDataTeacherAdd.name = $event.target.value"
                    />
                    <InputComponent
                        labelTitle="Acesso"
                        placeholderValue="Digite o acesso da escola"
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataTeacherAdd.acess_cod"
                        RightAction="display: none;"
                        @input="
                            formDataTeacherAdd.acess_cod = $event.target.value
                        "
                        autocomplete="new-text"
                    />
                    <div class="input-password">
                        <InputComponentPassword
                            labelTitle="Senha de acesso"
                            placeholderValue="Senha"
                            icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                            :value="formDataTeacherAdd.password"
                            @input="
                                formDataTeacherAdd.password =
                                    $event.target.value
                            "
                            typeValue="password"
                            RightAction="display: flex;"
                        />
                    </div>
                </div>
            </div>
            <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitForm()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--secondary-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                        />
                    </svg>
                    Cadastrar Professores
                </a>
            </div>
        </ModalComponent>

        <ModalComponentDeleted
            v-if="deletedModal"
            Titlevalue="Apagar professor"
        >
            <h1 class="deleted-title">
                Você realmente deseja apagar o professor
                <strong>{{ formDataDeleted.name }}</strong> ? Esta é uma ação
                permanente que não poderá ser desfeita
            </h1>
            <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalDeleted()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--black-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="deletedTeachers()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                        />
                    </svg>
                    Apagar Professor
                </a>
            </div>
        </ModalComponentDeleted>

        <ModalComponent
            v-if="showModalClassData"
            Titlevalue="Visualização de turmas"
        >
            <div class="modal-body-size">
                <h2>Detalhes sobre a turma</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassVisualize.name"
                        typeValue="text"
                        @input="
                            formDataClassVisualize.name = $event.target.value
                        "
                    />
                    <SelectComponent
                        disabled="true"
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassVisualize.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="
                            formDataClassVisualize.teacher_id =
                                $event.target.value
                        "
                    />
                </div>
                <div class="modal-content-address"></div>
            </div>
            <div class="modal-end">
                <a
                    style="margin-right: 5rem"
                    class="close-modal"
                    @click="closeModal()"
                >
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Fechar
                </a>
            </div>
        </ModalComponent>

        <ModalComponent
            v-if="ShowModalClassCreation"
            Titlevalue="Cadastro de turmas"
        >
            <div class="modal-body-size">
                <h2>Detalhes sobre a turma</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassAdd.name"
                        typeValue="text"
                        @input="formDataClassAdd.name = $event.target.value"
                    />

                    <InputComponent
                        labelTitle="Ano"
                        placeholderValue="Ano"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassAdd.year"
                        typeValue="text"
                        @input="formDataClassAdd.year = $event.target.value"
                    />

                    <SelectComponent
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassAdd.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="
                            formDataClassAdd.teacher_id = $event.target.value
                        "
                    />

                    <NewSelectComponent
                        labelTitle="Turno"
                        placeholderValue="Turno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="formDataClassAdd.shift"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataClassAdd.shift = $event.target.value"
                    >
                        <option value="morning">Matutino</option>
                        <option value="afternoon">Vespertino</option>
                        <option value="night">Noturno</option>
                    </NewSelectComponent>
                </div>
            </div>
            <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitFormClass()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--secondary-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                        />
                    </svg>
                    Cadastrar Turma
                </a>
            </div>
        </ModalComponent>

        <ModalComponent
            v-if="updateModalClassData"
            Titlevalue="Atualizar turma"
        >
            <div class="modal-body-size">
                <h2>Detalhes sobre a turma</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassUpdated.name"
                        typeValue="text"
                        @input="formDataClassUpdated.name = $event.target.value"
                    />

                    <InputComponent
                        labelTitle="Ano"
                        placeholderValue="Ano"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassUpdated.year"
                        typeValue="text"
                        @input="formDataClassUpdated.year = $event.target.value"
                    />

                    <SelectComponent
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassUpdated.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="
                            formDataClassUpdated.teacher_id =
                                $event.target.value
                        "
                    />
                    <NewSelectComponent
                        labelTitle="Turno"
                        placeholderValue="Turno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="formDataClassUpdated.shift"
                        valueField="id"
                        RightAction="display: none;"
                        @input="
                            formDataClassUpdated.shift = $event.target.value
                        "
                    >
                        <option value="">Selecione uma opção</option>
                        <option value="morning">Matutino</option>
                        <option value="afternoon">Vespertino</option>
                        <option value="night">Noturno</option>
                    </NewSelectComponent>
                </div>
                <div class="modal-content-address"></div>
            </div>
            <div class="modal-end">
                <a
                    style="margin-right: 5rem"
                    class="close-modal"
                    @click="closeModalUpdated()"
                >
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Fechar
                </a>
                <a class="school-add" @click="updateClassDataForm()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--secondary-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                        />
                    </svg>
                    Atualizar turma
                </a>
            </div>
        </ModalComponent>

        <ModalComponentDeleted
            v-if="deletedClassDataModal"
            Titlevalue="Apagar turma"
        >
            <h1 class="deleted-title">
                Você realmente deseja apagar a turma
                <strong>{{ formDataClassDeleted.name }}</strong> ? Esta é uma
                ação permanente que não poderá ser desfeita
            </h1>
            <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalDeleted()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--black-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="deleteClassData()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                        />
                    </svg>
                    Apagar turma
                </a>
            </div>
        </ModalComponentDeleted>

        <ModalComponent
            v-if="showModalStudentCreation"
            Titlevalue="Cadastro de alunos"
        >
            <div class="modal-body-size">
                <h2>Criação do Aluno</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome do aluno"
                        placeholderValue="Nome do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataStudentAdd.name"
                        typeValue="text"
                        @input="formDataStudentAdd.name = $event.target.value"
                    />

                    <NewSelectComponent
                        labelTitle="Sexo"
                        placeholderValue="Sexo"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="formDataStudentAdd.gender"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataStudentAdd.gender = $event.target.value"
                    >
                        <option value="male">Masculino</option>
                        <option value="female">Feminino</option>
                    </NewSelectComponent>

                    <InputComponent
                        labelTitle="Data de Nascimento"
                        placeholderValue="Data da Nascimento"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        typeValue="date"
                        :value="formDataStudentAdd.date_of_birth"
                        @input="
                            formDataStudentAdd.date_of_birth =
                                $event.target.value
                        "
                    />

                    <InputComponent
                        labelTitle="Data da Matrícula"
                        placeholderValue="Data da Matrícula"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        typeValue="date"
                        :value="formDataStudentAdd.enrollment_date"
                        @input="
                            formDataStudentAdd.enrollment_date =
                                $event.target.value
                        "
                    />

                    <InputComponent
                        labelTitle="Matrícula"
                        placeholderValue="Matrícula"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataStudentAdd.enrollment"
                        typeValue="text"
                        @input="formDataStudentAdd.enrollment = $event.target.value"
                    />

                    <NewSelectComponent
                        labelTitle="Aluno é PCD?"
                        placeholderValue="Aluno é PCD?"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        typeValue="select"
                        :value="formDataStudentAdd.people_with_disabilities"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataStudentAdd.people_with_disabilities = $event.target.value"
                    >
                        <option v-bind:value="true">Sim</option>
                        <option v-bind:value="false">Não</option>
                    </NewSelectComponent>
                </div>
            </div>
            <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitStudentForm()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--secondary-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                        />
                    </svg>
                    Cadastrar Alunos
                </a>
            </div>
        </ModalComponent>

        <ModalComponent
            v-if="showModalStudentData"
            Titlevalue="Visualização de aluno"
        >
            <div class="modal-body-size">
                <h2>Detalhes sobre o aluno</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome do aluno"
                        placeholderValue="Nome do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataStudentVisualize.name"
                        typeValue="text"
                        @input="
                            formDataStudentVisualize.name = $event.target.value
                        "
                    />

                    <SelectComponent
                        disabled="true"
                        labelTitle="Turma do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="ClassSchool"
                        typeValue="select"
                        :value="formDataStudentVisualize.group_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="
                            formDataStudentVisualize.group_id =
                                $event.target.value
                        "
                    />
                </div>
                <div class="modal-content-address"></div>
            </div>
            <div class="modal-end">
                <a
                    style="margin-right: 5rem"
                    class="close-modal"
                    @click="closeModal()"
                >
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Fechar
                </a>
            </div>
        </ModalComponent>

        <ModalComponentDeleted
            v-if="deletedStudentModal"
            Titlevalue="Apagar aluno"
        >
            <h1 class="deleted-title">
                Você realmente deseja apagar o aluno
                <strong>{{ formDataStudentDeleted.name }}</strong> ? Esta é uma
                ação permanente que não poderá ser desfeita
            </h1>
            <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalDeleted()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="var(--black-color)"
                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                        ></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="deleteStudentData()">
                    <svg
                        width="20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="red"
                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                        />
                    </svg>
                    Apagar Professor
                </a>
            </div>
        </ModalComponentDeleted>

        <userWelcomeComponent />
        <MenuComponent />

        <div class="register-content">
            <Breadcrumb.Root>
                <Breadcrumb.Content>
                    <Breadcrumb.Item>
                        <svg
                            style="margin-right: 4px"
                            height="16"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 640 512"
                        >
                            <path
                                d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320H234.7c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7H106.7C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2H261.3C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7H455.2c-2.1-5.2-3.2-10.9-3.2-16.4v-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192H490.7c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3v-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6v3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3l-2.9 1.7c-9.2 5.3-20.4 4-29.6-1.3s-16.1-14.5-16.1-25.1v-3.4c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9v3.4c0 10.6-6.9 19.8-16.1 25.1s-20.4 6.6-29.6 1.3l-2.9-1.7c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l2.9 1.7c9.2 5.3 13.7 15.8 13.7 26.4s-4.5 21.1-13.7 26.4l-3 1.7c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3l2.9-1.7c9.2-5.3 20.4-4 29.6 1.3s16.1 14.5 16.1 25.1v3.4c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9v-3.4c0-10.6 6.9-19.8 16.1-25.1s20.4-6.6 29.6-1.3l2.9 1.7c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-3-1.7c-9.2-5.3-13.7-15.8-13.7-26.4s4.5-21.1 13.7-26.4l3-1.7zM472 384a40 40 0 1 1 80 0 40 40 0 1 1 -80 0z"
                            />
                        </svg>
                        <a href="/Management"> Gerenciamento </a>
                    </Breadcrumb.Item>
                </Breadcrumb.Content>
            </Breadcrumb.Root>

            <template v-if="userType === 'admin'">
                <TitleComponent title="Cadastro de escolas" />
                <TableComponent TitleValue="" />
            </template>

            <template v-if="userType === 'school'">
                <TitleComponent title="Cadastro de Professores" />
                <TableComponentComponent
                    TitleValue=""
                    :TableHeader="['Professor', 'Acesso']"
                    :TableContent="formDataTeachersPreview"
                    :TableActions="true"
                    :TableActionVisibility="true"
                    :TableActionUpdate="true"
                    :TableAddButton="true"
                    :ButtonTitle="'Adicionar Professor'"
                    :OpenAddModal="OpenModalTeachersCreation"
                    @viewDetails="ShowSchoolTeachersData"
                    @updateAction="UpdateSchoolTeachersData"
                    @deletedAction="deletedModalTeachersShow"
                    @exportData="exportTeachersData(toRaw(formDataTeachersPreview.value))"
                    @exportSampleData="exportTeachersSampleData"
                    @importData="handleImportTeachers"
                />
            </template>

            <template v-if="['school', 'teacher'].includes(userType)">
                <TitleComponent title="Cadastro de Turmas"/>
                <TableComponentComponent
                    class="tableClass"
                    TitleValue=""
                    :TableHeader="['Turma', 'Professor responsável']"
                    :TableContent="formDataClassPreview"
                    :TableActions="true"
                    :TableActionVisibility="true"
                    :TableActionUpdate="true"
                    :TableAddButton="true"
                    :ButtonTitle="'Adicionar Turma'"
                    :OpenAddModal="OpenModalClassCreation"
                    @viewDetails="ShowSchoolClassData"
                    @updateAction="UpdateSchoolClassData"
                    @deletedAction="deletedModalClassShow"
                    @exportData="exportClassesData(toRaw(formDataClassPreview.value))"
                    @exportSampleData="exportClassesSampleData"
                    @importData="handleImportClasses"
                />
            </template>

            <template v-if="['school', 'teacher'].includes(userType)">
                <TitleComponent title="Cadastro de alunos" />
                <TableComponentComponent
                    TitleValue=""
                    :TableHeader="['Nome do aluno', 'Matrícula']"
                    :TableContent="formDataStudentPreview"
                    :TableActions="true"
                    :TableActionVisibility="true"
                    :TableActionUpdate="false"
                    :TableAddButton="true"
                    :TableUpdateAction="false"
                    :ButtonTitle="'Adicionar aluno'"
                    :OpenAddModal="OpenModalStudentCreation"
                    @viewDetails="ShowStudentData"
                    @deletedAction="deletedModalStudentShow"
                    @exportData="exportStudentsData(toRaw(formDataStudentExport.value))"
                    @exportSampleData="exportStudentsSampleData"
                    @importData="handleImportStudents"
                ></TableComponentComponent>
            </template>
        </div>
    </div>
</template>

<style>
.modal-background {
    z-index: 9999;
}

.modal-end > .school-add {
    width: 16rem;
}
</style>