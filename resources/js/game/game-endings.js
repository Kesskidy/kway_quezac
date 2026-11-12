// Textes des fins (regle.md §7) : 3 fins gagnantes, 3 fins perdantes, choisies avec `won` et `scenario`.
// Jamais de K-Way qui prend l'eau ni de bouteille qui fuit : la pluie peut seulement entrer dans la bouteille ouverte.

const WON = [
    { title: 'Gagné !', text: 'La bouteille est arrivée à la source, pleine de bulles. Laisse ton prénom et ton e-mail pour participer au tirage final des lots.' },
    { title: 'Gagné !', text: 'Pas une goutte dans la bouteille : la main a tenu bon jusqu’à la source. Laisse ton prénom et ton e-mail pour participer au tirage final des lots.' },
    { title: 'Gagné !', text: 'Averse traversée, bouteille intacte, bulles au rendez-vous. Laisse ton prénom et ton e-mail pour participer au tirage final des lots.' },
];

const LOST = [
    { title: 'Presque !', text: 'Une goutte s’est glissée dans la bouteille ouverte juste avant la source. Retente ta chance avec le code d’une autre bouteille.' },
    { title: 'Presque !', text: 'Le randonneur est bien arrivé à la source, mais ce n’est pas pour cette fois. Un autre code ?' },
    { title: 'Presque !', text: 'La dernière averse a eu le dernier mot. Garde le code de ta prochaine bouteille !' },
];

export function getEnding(won, scenario) {
    const list = won ? WON : LOST;
    return list[scenario] ?? list[0];
}
