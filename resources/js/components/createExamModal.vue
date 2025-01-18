<script setup>
    import {defineEmits} from "vue";

    const props = defineProps({
        Titlevalue: {
            type: String,
            required: true
        }
    })

    const emits = defineEmits([
        'closeModal',
    ]);

</script>

<template>
    <div class="modal-background">
        <div class="modal-content" @click="emits('closeModal');">
            <div class="exit-button">
                <svg  style="margin-right: 4px"
                      fill="var(--secondary-color)"
                      height="25"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 640 512">
                    <path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/>
                </svg>
            </div>
            <div class="modal-title">
                <h1>{{ props.Titlevalue }}</h1>
                <hr>
            </div>
            <div class="modal-body">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style>
    .exit-button {
        display: flex;
        width: 100%;
        justify-content: right;
    }
    .modal-background {
        left: 0 !important;
        top: 0 !important;

        margin: 0 auto !important;

        position: fixed !important;

        height: 100vh !important;
        width: 100vw !important;

        background-color: rgba(0, 0, 0, 0.219);

        display: flex;
        justify-content: center;
        align-items: center;

        .modal-content::-webkit-scrollbar {
            width: 8px;
            height: 8px;            }

        .modal-content::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        & .modal-content{
            scrollbar-width: thin;
            max-height: 96vh;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 70%;
            border-radius: 0 0;

            & .modal-title {
                width: 100%;
                padding: 0.5rem 0;
                text-align: center;
                & h1 {
                    color: var(--primary-color);
                    margin: 1rem 0;
                }
                & hr {
                    background-color: var(--primary-color);
                    height: 2px;
                    border: 0;
                }
            }

            & .modal-body{
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 2rem 0 0 0;
                height: 100%;
                width: 100%;
                background-color: white;
                border-radius:  0 0 5rem 5rem;
        }
    }
}

.modal-background {
  & .modal-content {
    width: 90%;
  }
}

</style>
