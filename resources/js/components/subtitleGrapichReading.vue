<template>
    <div>
        <div class="subtitle-reading">
            <div class="item">
                <div class="subtitle-writing pre-syllabic"></div>
                <p>Não Leitor</p>
            </div>
            <div class="item">
                <div class="subtitle-writing syllabic"></div>
                <p>Leitor de silábas</p>
            </div>
            <div class="item">
                <div class="subtitle-writing syllabic-alphabetical"></div>
                <p>Leitor sem fluência</p>
            </div>
            <div class="item">
                <div class="subtitle-writing phrase-reader"></div>
                <p>Leitor de frases</p>
            </div>
            <div class="item">
                <div class="subtitle-writing alphabetical"></div>
                <p>Leitor de palavras</p>
            </div>
            <div class="item">
                <div class="subtitle-writing spelling"></div>
                <p>Leitor com fluência</p>
            </div>
        </div>
    </div>
</template>

<style>
.subtitle-reading {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(1, 30px);

    & .subtitle-writing {
        height: 15px;
        width: 15px;
        border-radius: 100%;
    }

    .item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    & hr {
        height: 15px;
        background-color: var(--black-color);
        border-radius: 3rem;
        border: 0;
        width: 2px;
    }

    & .pre-syllabic {
        background-color: red;
    }

    & .syllabic {
        background-color: #FF5C00;
    }

    & .syllabic-alphabetical {
        background-color: #FFCB00;
    }

    & .phrase-reader {
        background-color: #5EFF12;
    }

    & .alphabetical {
        background-color: #76AA3B;
    }

    & .spelling {
        background-color: #0D5413;
    }
}
</style>