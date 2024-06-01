<template>
    <div class="subtitle">
        <div class="item">
            <div class="subtitle-writing pre-syllabic"></div>
            <p>Pré-silábico</p>
        </div>
        <div class="item">
            <div class="subtitle-writing syllabic"></div>
            <p>Pré-silábico</p>
        </div>
        <div class="item">
            <div class="subtitle-writing syllabic-alphabetical"></div>
            <p>Silábico Alfabético</p>
        </div>
        <div class="item">
            <div class="subtitle-writing alphabetical"></div>
            <p>Alfabético</p>
        </div>
        <div class="item">
            <div class="subtitle-writing spelling"></div>
            <p>Ortográfico</p>
        </div>
    </div>
</template>

<style>
.subtitle {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(1, 30px);

    & .subtitle-writing{
        display: flex;
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

    & .pre-syllabic{
        background-color: red;
    }

    & .syllabic{
        background-color: #FF5C00;
    }

    & .syllabic-alphabetical{
        background-color: #FFCB00;
    }

    & .alphabetical {
        background-color: #76AA3B;
    }

    & .spelling {
        background-color: #0D5413;
    }
}
</style>