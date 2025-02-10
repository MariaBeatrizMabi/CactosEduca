<script setup>
import {defineEmits, defineProps, ref} from 'vue';
import LoadingComponent from '../components/loading.vue'
import {Dropdown} from '../components/Dropdown';

const isLoading = ref(false);

const props = defineProps({
    TitleValue: {
        type: String,
        required: true
    },
    TableHeader: {
        type: Array,
        required: true,
    },
    TableContent: {
        type: Array,
        required: true,
    },
    TableActions: {
        type: Boolean,
        required: true,
    },
    TableActionVisibility: {
        type: Boolean,
        required: true,
    },
    TableActionUpdate: {
        type: Boolean,
        required: true,
        default: true
    },
    TableActionDelete: {
        type: Boolean,
        required: false,
        default: true
    },
    TableAddButton: {
        type: Boolean,
        required: true,
    },
    ButtonTitle: {
        type: String,
        required: true,
    },
    OpenAddModal: {
        type: Boolean,
        required: true,
    },
    InsertExam: {
        type: Boolean,
        required: false,
        default: false
    },
    ExportLiteracyDocument: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const tooltipVisible = ref(false);

const emits = defineEmits([
    'viewDetails',
    'updateAction',
    'deletedAction',
    'exportData',
    'exportSampleData',
    'importData',
    'createExam',
    'exportLiteracyDocument'
]);

const showTooltip = (elementId) => {
    const element = document.getElementById(elementId)
    element.classList.add('d-flex')
    element.classList.remove('d-none')
}

const hideTooltip = (elementId) => {
    const element = document.getElementById(elementId)
    element.classList.remove('d-flex')
    element.classList.add('d-none')
}

</script>

<template>
    <LoadingComponent :isLoading="isLoading"/>

    <div class="tableContent">
        <div v-if="TableAddButton" class="showModalContent">
            <a class="showModalInfo" @click="OpenAddModal">
                <svg viewBox="0 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"
                        fill="var(--secondary-color)"/>
                </svg>
                {{ ButtonTitle }}
            </a>
            <Dropdown.Root>
                <Dropdown.Trigger>
                    <svg viewBox="0 0 448 512" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"
                            fill="var(--secondary-color)"/>
                    </svg>
                </Dropdown.Trigger>
                <Dropdown.Portal>
                    <Dropdown.Content>
                        <Dropdown.Item @click="$emit('importData')">
                            <svg viewBox="0 0 384 512" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                    fill="var(--secondary-color)"/>
                            </svg>
                            Importar dados
                        </Dropdown.Item>
                        <Dropdown.Item @click="$emit('exportSampleData')">
                            <svg viewBox="0 0 384 512" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                    fill="var(--secondary-color)"/>
                            </svg>
                            Exportar planilha modelo
                        </Dropdown.Item>
                        <Dropdown.Item @click="$emit('exportData')">
                            <svg viewBox="0 0 384 512" width="16" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M48 448L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"
                                    fill="var(--secondary-color)"/>
                            </svg>
                            Exportar dados
                        </Dropdown.Item>
                        <Dropdown.Item v-if="ExportLiteracyDocument" @click="$emit('exportLiteracyDocument')">
                            <svg fill="var(--secondary-color)" viewBox="0 0 512 512" width="20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M64 464l48 0 0 48-48 0c-35.3 0-64-28.7-64-64L0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 304l-48 0 0-144-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/>
                            </svg>
                            Exportar documento de alfabetização
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
                    <thead>
                    <tr>
                        <th v-for="header in TableHeader" :key="header">{{ header }}</th>
                        <th v-if="TableActions">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, rowIndex) in TableContent" :key="rowIndex">
                        <td v-for="(cell, cellIndex) in Object.entries(row).filter(([key]) => key !== 'id')"
                            :key="cellIndex">{{ cell[1] }}
                        </td>
                        <td v-if="TableActions">
                            <div class="actions">
                                <div
                                    class="show"
                                    @click="$emit('viewDetails', row.id)"
                                    @mouseleave="hideTooltip(`${rowIndex}-show-class`)"
                                    @mouseover="showTooltip(`${rowIndex}-show-class`)">
                                    <svg viewBox="0 0 576 512" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                                    </svg>
                                    <div :id="`${rowIndex}-show-class`" class="tooltip d-none">
                                        Visualizar informações da turma
                                    </div>
                                </div>
                                <div
                                    v-if="InsertExam"
                                    class="intervention"
                                    @click="$emit('createExam', row.id)"
                                    @mouseleave="hideTooltip(`${rowIndex}-create-exam`)"
                                    @mouseover="showTooltip(`${rowIndex}-create-exam`)"
                                >
                                    <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path
                                            d="M183.6 42.4C177.5 35.8 169 32 160 32s-17.5 3.8-23.6 10.4l-88 96c-11.9 13-11.1 33.3 2 45.2s33.3 11.1 45.2-2L128 146.3 128 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-301.7 32.4 35.4c11.9 13 32.2 13.9 45.2 2s13.9-32.2 2-45.2l-88-96zM320 320c0 17.7 14.3 32 32 32l50.7 0-73.4 73.4c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8l128 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-50.7 0 73.4-73.4c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8l-128 0c-17.7 0-32 14.3-32 32zM416 32c-12.1 0-23.2 6.8-28.6 17.7l-64 128-16 32c-7.9 15.8-1.5 35 14.3 42.9s35 1.5 42.9-14.3l7.2-14.3 88.4 0 7.2 14.3c7.9 15.8 27.1 22.2 42.9 14.3s22.2-27.1 14.3-42.9l-16-32-64-128C439.2 38.8 428.1 32 416 32zM395.8 176L416 135.6 436.2 176l-40.4 0z"/>
                                    </svg>
                                    <div :id="`${rowIndex}-create-exam`" class="tooltip d-none">
                                        Cadastrar sondagem
                                    </div>
                                </div>
                                <div
                                    v-if="TableActionUpdate"
                                    class="edit"
                                    @click="$emit('updateAction', row.id)"
                                    @mouseleave="hideTooltip(`${rowIndex}-update-class`)"
                                    @mouseover="showTooltip(`${rowIndex}-update-class`)"
                                >
                                    <svg viewBox="0 0 512 512" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                                    </svg>
                                    <div :id="`${rowIndex}-update-class`" class="tooltip d-none">
                                        Atualizar turma
                                    </div>
                                </div>
                                <div
                                    v-if="TableActionDelete"
                                    class="deleted"
                                    @click="$emit('deletedAction', row.id)"
                                    @mouseleave="hideTooltip(`${rowIndex}-delete-class`)"
                                    @mouseover="showTooltip(`${rowIndex}-delete-class`)"
                                >
                                    <svg viewBox="0 0 512 512" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                                    </svg>
                                    <div :id="`${rowIndex}-delete-class`" class="tooltip-delete d-none">
                                        Deletar turma
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style>
.titleTable {
    height: 3.5rem;
}

.table-wrapper {
    position: relative;
}

.table-wrapper table {
    width: 100%;
}

.table-scroll {
    overflow: auto;
    border: solid 1px var(--secondary-color);
    border-width: 0 1px;
}

.table-scroll table tr {
    & > td:first-child, th:first-child {
        border-left: 0;
    }

    & > td:last-child, th:last-child {
        border-right: 0;
    }
}

.tooltip {
    position: absolute;
    margin-top: -50px;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    opacity: 0.9;
}

.tooltip-delete {
    position: absolute;
    margin-top: -120px;
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    opacity: 0.9;
}

.d-none {
    display: none;
}

.d-flex {
    display: flex;
}
</style>
