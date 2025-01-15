<script setup>
    import { ref } from 'vue'

    const Titlevalue = ref()

    const props = defineProps({
        Titlevalue: {
            type: String,
            required: true
        }
    })

</script>

<template>
    <div class="modal-background">
        <div class="modal-content">
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

        .modal-content::-webkit-scrollbar{
            display: none;
        }

        & .modal-content{
            max-height: 96vh; /* Limita a altura do modal */
            overflow-y: auto; /* Permite o scroll vertical */
            padding: 20px;
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 70%;
            border-radius: 5rem 5rem;

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
