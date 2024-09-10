<script setup>
import {ref, onMounted, defineProps, toRaw} from "vue";
import axios from "axios";
import { api } from "../services/api";
import ModalComponent from "../components/modal.vue";
import InputComponent from "../components/input.vue";
import LoadingComponent from "../components/loading.vue";
import ModalComponentDeleted from "../components/modalComponentShort.vue";
import InputComponentPassword from "../components/inputPassword.vue";
import SelectComponent from "../components/select.vue";
import Combobox from "./Combobox";
import { Dropdown } from "./Dropdown";
import { importSchools } from "../services/import";
import { exportSchoolsData, exportSchoolsSampleData } from "../services/export";

const showModal = ref();
const showModalUpdated = ref();
const showModalData = ref();
const deletedModal = ref();

const idToUpdate = ref(null);
const idToDeleted = ref(null);

const isLoading = ref(false);

const props = defineProps({
    TitleValue: {
        type: String,
        required: true,
    },
});

function resetForm() {
    formDataAdd.value = {
        name: "",
        location_id: "",
        city_name: "",
        acess_cod: "",
        password: "",
        type: "school",
    };
}

let formData = ref({
    id: "",
    name: "",
    location_id: "",
    acess_cod: "",
    city_name: "",
    password: "",
});

const formDataAdd = ref({
    name: "",
    location_id: "",
    city_id: "",
    acess_cod: "",
    password: "",
    type: "school",
});

let formDataUpdate = ref({
    id: "",
    name: "",
    location_id: "",
    acess_cod: "",
    city_name: "",
    password: "",
});

let formDataShow = ref({
    id: "",
    name: "",
    location_id: "",
    acess_cod: "",
    city_name: "",
    password: "",
});

const cities = ref([]);
const locations = ref([]);

const searchCity = ref("");

async function getTableData() {
    try {
        const { data } = await axios.get("/ManagementSchool");

        const formattedData = [];

        data.forEach((city) => {
            city.schools.forEach((school) => {
                formattedData.push({
                    id: school.id,
                    name: school.name,
                    city_name: city.city,
                    location_id: school.location,
                    acess_cod: school.user.acess_cod,
                });
            });
        });

        formData.value = formattedData;
    } catch (error) {
        console.error(error);
    }
}

async function ShowSchoolData(id) {
    showModalData.value = true;
    try {
        const response = await axios.get(`/ManagementSchool/${id}`);

        const school = response.data;

        formDataShow.value = {
            id: school.id,
            name: school.name,
            location_id: school.location_id,
            city_name: school.city_id.name,
            acess_cod: school.acess_cod,
            type: "school",
        };
    } catch (error) {
        console.error(error);
    }
}

function openModal() {
    resetForm();
    showModal.value = true;
}

function closeModal() {
    resetForm();
    showModalUpdated.value = false;
}

function closeModalAdd() {
    resetForm();
    showModal.value = false;
}

function closeModalShow() {
    resetForm();
    showModalData.value = false;
}

function closeModalDeleted() {
    resetForm();
    deletedModal.value = false;
}

async function submitForm() {
    try {
        isLoading.value = true;

        await axios.post("/ManagementSchoolCreate", formDataAdd.value);

        getTableData();

        showModal.value = false;

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
        isLoading.value = false;
    }
}

async function fetchSchoolData(id) {
    showModalUpdated.value = true;

    try {
        const { data: managementSchool } = await api.get(
            `/api/management-schools/${id}`
        );

        idToUpdate.value = id;

        formDataUpdate.value = {
            id: managementSchool.id,
            name: managementSchool.name,
            location_id: managementSchool.location.id,
            city_id: managementSchool.city_id.id,
            acess_cod: managementSchool.acess_cod,
            type: "school",
        };
    } catch (error) {
        console.error(error);
    }
}

async function updateDataForm(dataToUpdate) {
    try {
        await api.put(
            `/api/management-schools/${idToUpdate.value}`,
            formDataUpdate.value
        );

        showModalUpdated.value = false;
        isLoading.value = true;

        getTableData();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function deletedModalShow(id) {
    deletedModal.value = true;
    resetForm();
    const response = await api.get(`/ManagementSchool/${id}`);

    idToDeleted.value = id;

    formDataShow.value = {
        name: response.data.name,
    };
}

async function deleted() {
    try {
        await api.delete(`/api/management-schools/${idToDeleted.value}`);
        deletedModal.value = false;
        isLoading.value = true;

        getTableData();
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

async function getCities() {
    const { data } = await api.get("/api/cities");
    return data;
}

async function getLocations() {
    const { data } = await api.get("/locations");
    return data;
}

async function createCity(name) {
    const { data } = await api.post("/api/cities", { name });
    cities.value = await getCities();
    formDataAdd.value.city_id = data.id;
}

async function deleteCity(id) {
    await api.delete(`/api/cities/${id}`);
    cities.value = await getCities();
}

async function handleSelectedCityChange(value) {
    const cityAlreadyExists = cities.value.find(({ name }) => name === value);

    if (cityAlreadyExists) {
        formDataAdd.value.city_id = cityAlreadyExists.id;
        return;
    }

    await createCity(value);
}

async function handleSelectedCityChangeUpdate(value) {
    const cityAlreadyExists = cities.value.find(({ name }) => name === value);

    if (cityAlreadyExists) {
        formDataUpdate.value.city_id = cityAlreadyExists.id;
        return;
    }

    await createCity(value);
}

async function handleImportSchools() {
    if (!Array.isArray(cities.value)) {
        cities.value = [];
    }
    if (!Array.isArray(locations.value)) {
        locations.value = [];
    }

    await importSchools(cities.value, locations.value);
    await getTableData();
}

async function handleExportSchools() {
    exportSchoolsData(toRaw(formData.value));
}

onMounted(async () => {
    cities.value = await getCities();
    locations.value = await getLocations();
});

onMounted(
    (isLoading.value = true),
    getTableData(),
    setTimeout(() => (isLoading.value = false), 800)
);
</script>

<template>
    <LoadingComponent :isLoading="isLoading" />
    <ModalComponent v-if="showModalData" Titlevalue="Visualização de escolas">
        <div class="modal-body-size">
            <h2>Detalhes sobre a escola</h2>
            <div class="modal-content-details">
                <InputComponent
                    disabled="true"
                    labelTitle="Nome da escola"
                    placeholderValue="Nome da escola"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataShow.name"
                    @input="formDataShow.name = $event.target.value"
                />
                <InputComponent
                    disabled="true"
                    labelTitle="Acesso"
                    placeholderValue="Digite o acesso da escola"
                    icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                    :value="formDataShow.acess_cod"
                    @input="formDataShow.acess_cod = $event.target.value"
                />
                <InputComponentPassword
                    disabled="true"
                    labelTitle="Senha de acesso"
                    placeholderValue="Senha"
                    icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                    :value="formDataShow.password"
                    @input="formDataShow.password = $event.target.value"
                />
            </div>
            <h2>Localização da escola</h2>
            <div class="modal-content-address">
                <InputComponent
                    disabled
                    labelTitle="Múnicipio da escola"
                    placeholderValue="Digite o múnicipio"
                    icon="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120zm8 80.4c3.5-6.9 6.7-13.8 9.6-20.6c.5-1.2 1-2.5 1.5-3.7l116-46.4C558.9 123.4 576 135 576 152V422.8c0 9.8-6 18.6-15.1 22.3L416 503V200.4zM137.6 138.3c2.4 14.1 7.2 28.3 12.8 41.5c2.9 6.8 6.1 13.7 9.6 20.6V451.8L32.9 502.7C17.1 509 0 497.4 0 480.4V209.6c0-9.8 6-18.6 15.1-22.3l122.6-49zM327.8 332c13.9-17.4 35.7-45.7 56.2-77V504.3L192 449.4V255c20.5 31.3 42.3 59.6 56.2 77c20.5 25.6 59.1 25.6 79.6 0zM288 152a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                    :value="formDataShow.city_name"
                />
                <SelectComponent
                    disabled="true"
                    labelTitle="Localidade"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    routerPath="locations"
                    typeValue="select"
                    :value="formDataShow.id"
                    valueField="id"
                    RightAction="display: none;"
                    @input="formDataShow.id = $event.target.value"
                />
            </div>
        </div>
        <div class="modal-end">
            <a
                style="margin-right: 5rem"
                class="close-modal"
                @click="closeModalShow()"
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

    <ModalComponent v-if="showModal" Titlevalue="Cadastro de escolas">
        <div class="modal-body-size">
            <h2>Detalhes sobre a escola</h2>
            <div class="modal-content-details">
                <InputComponent
                    labelTitle="Nome da escola"
                    placeholderValue="Nome da escola"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataAdd.name"
                    typeValue="text"
                    RightAction="display: none;"
                    @input="formDataAdd.name = $event.target.value"
                />
                <InputComponent
                    labelTitle="Acesso"
                    placeholderValue="Digite o acesso da escola"
                    icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                    :value="formDataAdd.acess_cod"
                    RightAction="display: none;"
                    @input="formDataAdd.acess_cod = $event.target.value"
                    autocomplete="new-text"
                />
                <div class="input-password">
                    <InputComponentPassword
                        labelTitle="Senha de acesso"
                        placeholderValue="Senha"
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataAdd.password"
                        @input="formDataAdd.password = $event.target.value"
                        typeValue="password"
                        RightAction="display: flex;"
                    />
                </div>
            </div>
            <h2>Localização da escola</h2>
            <div class="modal-content-address">
                <div>
                    <label class="city-label">Município da Escola</label>
                    <Combobox.Root
                        style="margin-top: 3px"
                        :value="
                            cities.find(({ id }) => formDataAdd.city_id === id)
                                ?.name
                        "
                        :search="searchCity"
                        @update:search="searchCity = $event"
                        @update:value="handleSelectedCityChange"
                    >
                        <Combobox.Input />
                        <Combobox.Content>
                            <Combobox.Viewport>
                                <Combobox.Empty>
                                    <Combobox.Item
                                        v-if="searchCity"
                                        :key="searchCity"
                                        :value="searchCity"
                                    >
                                        Criar "{{ searchCity }}"
                                    </Combobox.Item>
                                </Combobox.Empty>

                                <Combobox.Group>
                                    <Combobox.Item
                                        v-if="
                                            searchCity &&
                                            !cities.find(
                                                ({ name }) =>
                                                    name.toLowerCase() ==
                                                    searchCity.toLowerCase()
                                            )
                                        "
                                        :key="searchCity"
                                        :value="searchCity"
                                    >
                                        Criar "{{ searchCity }}"
                                    </Combobox.Item>

                                    <Combobox.Item
                                        v-for="city in cities"
                                        :value="city.name"
                                        :key="city.name"
                                    >
                                        <div class="combobox-item-content">
                                            <span>{{ city.name }}</span>

                                            <button
                                                class="delete-city"
                                                v-if="
                                                    !formData.find(
                                                        ({ city_name }) =>
                                                            city_name ==
                                                            city.name
                                                    )
                                                "
                                                @click="deleteCity(city.id)"
                                            >
                                                <svg
                                                    height="14"
                                                    viewBox="0 0 18 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M5.43214 0.725977L5.14286 1.3125H1.28571C0.574554 1.3125 0 1.89902 0 2.625C0 3.35098 0.574554 3.9375 1.28571 3.9375H16.7143C17.4254 3.9375 18 3.35098 18 2.625C18 1.89902 17.4254 1.3125 16.7143 1.3125H12.8571L12.5679 0.725977C12.3509 0.278906 11.9049 0 11.4188 0H6.58125C6.09509 0 5.64911 0.278906 5.43214 0.725977ZM16.7143 5.25H1.28571L2.1375 19.1543C2.20179 20.192 3.04554 21 4.06205 21H13.9379C14.9545 21 15.7982 20.192 15.8625 19.1543L16.7143 5.25Z"
                                                        fill="#253138"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </Combobox.Item>
                                </Combobox.Group>
                            </Combobox.Viewport>
                        </Combobox.Content>
                    </Combobox.Root>
                </div>

                <SelectComponent
                    labelTitle="Localidade"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    routerPath="locations"
                    typeValue="select"
                    :value="formDataAdd.location_id"
                    valueField="id"
                    RightAction="display: none;"
                    @input="formDataAdd.location_id = $event.target.value"
                />
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
                Cadastrar Escola
            </a>
        </div>
    </ModalComponent>

    <ModalComponent v-if="showModalUpdated" Titlevalue="Atualização de escolas">
        <div class="modal-body-size">
            <h2>Detalhes sobre a escola</h2>
            <div class="modal-content-details">
                <InputComponent
                    labelTitle="Nome da escola"
                    placeholderValue="Nome da escola"
                    icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                    :value="formDataUpdate.name"
                    @input="formDataUpdate.name = $event.target.value"
                />
                <InputComponent
                    labelTitle="Acesso"
                    placeholderValue="Digite o acesso da escola"
                    icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
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
            <h2>Localização da escola</h2>
            <div class="modal-content-address">
                <div>
                    <label class="city-label">Município da Escola</label>
                    <Combobox.Root
                        style="margin-top: 3px"
                        :value="
                            cities.find(
                                ({ id }) => formDataUpdate.city_id === id
                            )?.name
                        "
                        :search="searchCity"
                        @update:search="searchCity = $event"
                        @update:value="handleSelectedCityChangeUpdate"
                    >
                        <Combobox.Input />
                        <Combobox.Content>
                            <Combobox.Viewport>
                                <Combobox.Empty>
                                    <Combobox.Item
                                        v-if="searchCity"
                                        :key="searchCity"
                                        :value="searchCity"
                                    >
                                        Criar "{{ searchCity }}"
                                    </Combobox.Item>
                                </Combobox.Empty>

                                <Combobox.Group>
                                    <Combobox.Item
                                        v-if="
                                            searchCity &&
                                            !cities.find(
                                                ({ name }) =>
                                                    name.toLowerCase() ==
                                                    searchCity.toLowerCase()
                                            )
                                        "
                                        :key="searchCity"
                                        :value="searchCity"
                                    >
                                        Criar "{{ searchCity }}"
                                    </Combobox.Item>

                                    <Combobox.Item
                                        v-for="city in cities"
                                        :value="city.name"
                                        :key="city.name"
                                    >
                                        <div class="combobox-item-content">
                                            <span>{{ city.name }}</span>

                                            <button
                                                class="delete-city"
                                                v-if="
                                                    !formData.find(
                                                        ({ city_name }) =>
                                                            city_name ==
                                                            city.name
                                                    )
                                                "
                                                @click="deleteCity(city.id)"
                                            >
                                                <svg
                                                    height="14"
                                                    viewBox="0 0 18 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M5.43214 0.725977L5.14286 1.3125H1.28571C0.574554 1.3125 0 1.89902 0 2.625C0 3.35098 0.574554 3.9375 1.28571 3.9375H16.7143C17.4254 3.9375 18 3.35098 18 2.625C18 1.89902 17.4254 1.3125 16.7143 1.3125H12.8571L12.5679 0.725977C12.3509 0.278906 11.9049 0 11.4188 0H6.58125C6.09509 0 5.64911 0.278906 5.43214 0.725977ZM16.7143 5.25H1.28571L2.1375 19.1543C2.20179 20.192 3.04554 21 4.06205 21H13.9379C14.9545 21 15.7982 20.192 15.8625 19.1543L16.7143 5.25Z"
                                                        fill="#253138"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </Combobox.Item>
                                </Combobox.Group>
                            </Combobox.Viewport>
                        </Combobox.Content>
                    </Combobox.Root>
                </div>
                <SelectComponent
                    labelTitle="Localidade"
                    icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                    routerPath="locations"
                    typeValue="select"
                    :value="formDataUpdate.location_id"
                    valueField="id"
                    RightAction="display: none;"
                    @input="formDataUpdate.location_id = $event.target.value"
                />
            </div>
        </div>
        <div class="modal-end">
            <a class="close-modal" @click="closeModal()">
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
            <a ref="link" @click="updateDataForm()">
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
                Atualizar Escola
            </a>
        </div>
    </ModalComponent>

    <ModalComponentDeleted v-if="deletedModal" Titlevalue="Apagar escola">
        <h1 class="deleted-title">
            Você realmente deseja apagar a escola
            <strong> {{ formDataShow.name }}?</strong> Esta é uma ação
            permantente que não poderá ser desfeita
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
            <a ref="link" @click="deleted()">
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
                Apagar Escola
            </a>
        </div>
    </ModalComponentDeleted>

    <div class="tableContent">
        <div class="showModalContent">
            <a class="showModalInfo" @click="openModal()">
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
                Adicionar Escola
            </a>

            <Dropdown.Root>
                <Dropdown.Trigger>
                    <svg
                        width="16"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"
                    >
                        <path
                            fill="var(--secondary-color)"
                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"
                        />
                    </svg>
                </Dropdown.Trigger>
                <Dropdown.Portal>
                    <Dropdown.Content>
                        <Dropdown.Item @click="handleImportSchools">
                            <svg
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512"
                            >
                                <path
                                    fill="var(--secondary-color)"
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                />
                            </svg>
                            Importar dados
                        </Dropdown.Item>
                        <Dropdown.Item @click="exportSchoolsSampleData">
                            <svg
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512"
                            >
                                <path
                                    fill="var(--secondary-color)"
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                />
                            </svg>
                            Exportar planilha modelo
                        </Dropdown.Item>
                        <Dropdown.Item @click="handleExportSchools">
                            <svg
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512"
                            >
                                <path
                                    fill="var(--secondary-color)"
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                />
                            </svg>
                            Exportar dados
                        </Dropdown.Item>
                    </Dropdown.Content>
                </Dropdown.Portal>
            </Dropdown.Root>
        </div>

        <div class="titleTable">
            <h1>{{ TitleValue }}</h1>
        </div>
        <div class="table-wrapper">
            <div class="table-scroll">
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Múnicipio</th>
                        <th>Localidade</th>
                        <th>Código de acesso</th>
                        <th>Ações</th>
                    </tr>
                    <tr v-for="(data, index) in formData" :key="index">
                        <td>{{ data.name }}</td>
                        <td>{{ data.city_name }}</td>
                        <td>{{ data.location_id }}</td>
                        <td>{{ data.acess_cod }}</td>
                        <td>
                            <div class="actions">
                                <div
                                    class="show"
                                    @click="ShowSchoolData(data.id)"
                                >
                                    <svg
                                        width="18"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512"
                                    >
                                        <path
                                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="edit"
                                    @click="fetchSchoolData(data.id)"
                                >
                                    <svg
                                        width="18"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                    >
                                        <path
                                            d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="deleted"
                                    @click="deletedModalShow(data.id)"
                                >
                                    <svg
                                        width="18"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                    >
                                        <path
                                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
.city-label {
    font-size: 14px;
    padding-bottom: 10px;
    font-weight: 600;
    color: var(--black-color);
}
</style>

<style>
.delete-city {
    all: unset;
}

.modal-body-size {
    width: 80%;
    color: var(--black-color);
}

.modal-content-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin: 2rem 0;
    width: 100%;
}

.modal-content-address {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 100%;
    gap: 2rem 5rem;
    margin: 2rem 0;
}

.modal-end,
.modal-end-deleted {
    width: 100%;
    background-color: white;

    border-radius: 0 0 5rem 5rem;

    display: flex;

    justify-content: end;
    border-top: 2px solid var(--primary-color);

    & .close-modal {
        border: 1px solid red;
        color: red;
        width: 10rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 1rem 2rem 1rem 0;
    }

    & .close-modal:hover {
        transition: 0.3s;
        border: 1px solid white;
        color: white;
        background-color: red;

        & svg > path {
            fill: white;
        }
    }

    & a {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 1rem 5rem 1rem 0;
        display: flex;
        align-items: end;
        width: 13rem;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        border: 1px solid var(--secondary-color);
        background-color: white;
        align-items: center;
        color: var(--secondary-color);
        cursor: pointer;
    }

    a:hover {
        transition: 0.3s;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        & svg > path {
            fill: var(--primary-color);
        }
    }
}

.modal-end-deleted {
    border-top: 2px solid var(--black-color) !important;
    & a {
        border: 1px solid red;
        background-color: white;
        color: red;
    }

    a:hover {
        transition: 0.3s;
        color: white;
        background-color: red;
        border: 1px solid red;
        & svg > path {
            fill: white;
        }
    }

    & .close-modal-deleted {
        border: 1px solid var(--black-color);
        color: var(--black-color);
        width: 10rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 1rem 2rem 1rem 0;
    }

    & .close-modal-deleted:hover {
        transition: 0.3s;
        border: 1px solid white;
        color: white;
        background-color: var(--black-color);

        & svg > path {
            fill: white;
        }
    }
}

.input-password {
    display: flex;

    .input-formating {
        border-radius: 5rem !important;
    }

    & svg:last-child {
        background-color: var(--grap-color);
        border-radius: 5rem;
        width: 5.5rem;
        padding: 1.5rem;
    }
}

.deleted-title {
    color: red;
    width: 90%;
    font-size: 20px;
    padding: 2rem 0;
    font-weight: 400;
}

.tableContent {
    width: 84%;
    display: flex;
    flex-direction: column;
    padding: 0 0 5rem 0;
    justify-content: start;
}

.showModalContent {
    display: flex;
    justify-content: end;
    margin: 1rem 0 1rem 0;
    gap: 1rem;
}

.showModalInfo {
    display: flex;
    align-items: end;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    justify-content: center;
    border-radius: 2rem;
    border: 1px solid var(--secondary-color);
    background-color: white;
    align-items: center;
    color: var(--secondary-color);
    cursor: pointer;
    font-size: 1rem;
}

.showModalInfo:hover {
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
    & svg > path {
        fill: var(--primary-color);
    }
}

.titleTable {
    background-color: var(--secondary-color);
    z-index: -1000;

    color: white;
    width: 100%;
    text-align: center;

    padding: 0.5rem;
    font-size: 12px;

    border-radius: 1rem 1rem 0 0;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;

    background-color: rgb(255, 255, 255);

    & tr > th {
        padding: 1.5rem 1.5rem 1.5rem 1rem;
    }
}

td,
th {
    border: 1px solid var(--secondary-color);
    text-align: left;
    padding: 8px 1rem;
}

th:last-child {
    text-align: center;
}

tr:nth-child(even) {
    background-color: var(--grap-color);
}

.actions {
    display: flex;
    justify-content: center;
    gap: 3rem;

    & .show {
        display: flex;
        justify-content: center;
        padding: 0.5rem 1rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: var(--primary-color);
        }
    }

    & .show:hover {
        background-color: var(--primary-color);
        & svg > path {
            fill: rgb(255, 255, 255);
        }
    }

    & .edit {
        display: flex;
        justify-content: center;
        padding: 0.5rem 1rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: rgb(47, 0, 255);
        }
    }

    & .edit:hover {
        background-color: rgb(47, 0, 255);
        & svg > path {
            fill: rgb(255, 255, 255);
        }
    }

    & .deleted {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 3.5rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: rgb(255, 0, 0);
        }
    }

    & .deleted:hover {
        background-color: rgb(255, 0, 0);
        & svg > path {
            fill: rgb(255, 255, 255);
        }
    }
}

.combobox-item-content {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
}

@media (max-width: 700px) {
    .modal-content-details {
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
        margin: 1rem 0;
    }
    .modal-content-address {
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
        margin: 1rem 0;
    }

    .deleted-title {
        font-size: 16px;
    }

    .modal-end-deleted {
        & .close-modal-deleted {
            margin: 1rem 0;
            width: 70%;
        }
    }

    .modal-background-deleted {
        & .modal-content {
            height: 35%;
        }
    }

    .modal-end,
    .modal-end-deleted {
        flex-direction: column;
        align-items: center;
        & a {
            margin: 1rem 0;
            display: flex;
            align-items: end;
            width: 70%;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
        }

        & .close-modal {
            width: 70%;
            margin-right: 0 !important;
            margin: 1rem 0;
        }
    }
}

@media (max-width: 550px) {
    .actions {
        gap: 1rem;
        flex-direction: column;
        align-items: center;

        & .show {
            padding: 1rem;
        }

        & .edit {
            padding: 1rem;
        }

        & .deleted {
            padding: 1rem;
        }
    }
}
</style>
