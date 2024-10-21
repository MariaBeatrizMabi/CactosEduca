const data = {
    pre_syllabic: 'Pré silábico',
    syllabic: 'Silábico',
    alphabetical_syllabic: 'Silábico alfabético',
    alphabetical: 'Alfabético',
    not_reader: 'Não leitor',
    syllable_reader: 'Leitor de sílabas',
    word_reader: 'Leitor de palavras',
    sentence_reader: 'Leitor de frases',
    no_fluent_text_reader: 'Leitor de texto sem fluência',
    fluent_text_reader: 'Leitor de texto com fluência'
}

export function translate(key = '') {
    return data[key];
}
