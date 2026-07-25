// Philippine Region -> Province -> City/Municipality -> Barangay tree for
// cascading address selects.
//
// Region and Province cover the full, official PSA/PSGC list (17 regions,
// all provinces) so those two levels are a closed, authoritative dataset.
// City/Municipality is seeded with each province's capital (or another
// well-known city for provinces that already had richer demo data) rather
// than an exhaustive list — full city/barangay coverage is tens of
// thousands of entries. The City and Barangay inputs are searchable AND
// creatable in the UI, so producers can type a value that isn't pre-listed
// and it's accepted as free text rather than blocking the form.

export const addressTree = {
    'NCR - National Capital Region': {
        'Metro Manila': {
            'Makati City': ['Poblacion', 'Bel-Air', 'San Lorenzo', 'Guadalupe Nuevo'],
            'Quezon City': ['Diliman', 'Cubao', 'Fairview', 'Batasan Hills'],
            'Manila': ['Ermita', 'Malate', 'Sampaloc', 'Tondo'],
            'Pasig City': ['Ortigas Center', 'Kapitolyo', 'San Antonio'],
            'Taguig City': ['Bonifacio Global City', 'Ususan', 'Western Bicutan'],
        },
    },
    'CAR - Cordillera Administrative Region': {
        Abra: { Bangued: [] },
        Apayao: { Kabugao: [] },
        Benguet: { 'La Trinidad': [] },
        Ifugao: { Lagawe: [] },
        Kalinga: { 'Tabuk City': [] },
        'Mountain Province': { Bontoc: [] },
    },
    'Region I - Ilocos Region': {
        'Ilocos Norte': { 'Laoag City': [] },
        'Ilocos Sur': { 'Vigan City': [] },
        'La Union': { 'San Fernando City': [] },
        Pangasinan: { 'Dagupan City': [] },
    },
    'Region II - Cagayan Valley': {
        Batanes: { Basco: [] },
        Cagayan: { 'Tuguegarao City': [] },
        Isabela: { 'Ilagan City': [] },
        'Nueva Vizcaya': { Bayombong: [] },
        Quirino: { Cabarroguis: [] },
    },
    'Region III - Central Luzon': {
        Aurora: { Baler: [] },
        Bataan: { 'Balanga City': [] },
        Bulacan: {
            Malolos: ['Sto. Rosario', 'Barihan'],
            'San Jose del Monte': ['Tungkong Mangga', 'Muzon'],
        },
        'Nueva Ecija': { 'Palayan City': [] },
        Pampanga: {
            'San Fernando': ['Dolores', 'San Jose', 'Sto. Niño'],
            Angeles: ['Balibago', 'Malabanias'],
        },
        Tarlac: { 'Tarlac City': [] },
        Zambales: { Iba: [] },
    },
    'Region IV-A - CALABARZON': {
        Batangas: {
            'Batangas City': ['Poblacion', 'Alangilan'],
            Lipa: ['Marauoy', 'Balintawak'],
        },
        Cavite: {
            'Dasmariñas': ['Zone I', 'Salawag', 'San Agustin'],
            'Bacoor': ['Molino', 'Zapote'],
        },
        Laguna: {
            'Santa Rosa': ['Balibago', 'Tagapo'],
            'Calamba': ['Real', 'Canlubang'],
        },
        Quezon: { 'Lucena City': [] },
        Rizal: { 'Antipolo City': [] },
    },
    'Region IV-B - MIMAROPA': {
        Marinduque: { Boac: [] },
        'Occidental Mindoro': { Mamburao: [] },
        'Oriental Mindoro': { 'Calapan City': [] },
        Palawan: { 'Puerto Princesa City': [] },
        Romblon: { Romblon: [] },
    },
    'Region V - Bicol Region': {
        Albay: { 'Legazpi City': [] },
        'Camarines Norte': { Daet: [] },
        'Camarines Sur': { Pili: [] },
        Catanduanes: { Virac: [] },
        Masbate: { 'Masbate City': [] },
        Sorsogon: { 'Sorsogon City': [] },
    },
    'Region VI - Western Visayas': {
        Aklan: { Kalibo: [] },
        Antique: { 'San Jose de Buenavista': [] },
        Capiz: { 'Roxas City': [] },
        Guimaras: { Jordan: [] },
        Iloilo: { 'Iloilo City': [] },
        'Negros Occidental': { 'Bacolod City': [] },
    },
    'Region VII - Central Visayas': {
        Bohol: {
            Tagbilaran: ['Poblacion I', 'Poblacion II'],
        },
        Cebu: {
            'Cebu City': ['Lahug', 'Guadalupe', 'Talamban'],
            Mandaue: ['Centro', 'Tipolo'],
        },
        'Negros Oriental': { 'Dumaguete City': [] },
        Siquijor: { Siquijor: [] },
    },
    'Region VIII - Eastern Visayas': {
        Biliran: { Naval: [] },
        'Eastern Samar': { 'Borongan City': [] },
        Leyte: { 'Tacloban City': [] },
        'Northern Samar': { Catarman: [] },
        Samar: { 'Catbalogan City': [] },
        'Southern Leyte': { 'Maasin City': [] },
    },
    'Region IX - Zamboanga Peninsula': {
        'Zamboanga del Norte': { 'Dipolog City': [] },
        'Zamboanga del Sur': { 'Pagadian City': [] },
        'Zamboanga Sibugay': { Ipil: [] },
    },
    'Region X - Northern Mindanao': {
        Bukidnon: { 'Malaybalay City': [] },
        Camiguin: { Mambajao: [] },
        'Lanao del Norte': { Tubod: [] },
        'Misamis Occidental': { 'Oroquieta City': [] },
        'Misamis Oriental': { 'Cagayan de Oro City': [] },
    },
    'Region XI - Davao Region': {
        'Davao de Oro': { Nabunturan: [] },
        'Davao del Norte': { 'Tagum City': [] },
        'Davao del Sur': {
            'Davao City': ['Buhangin', 'Talomo', 'Poblacion'],
        },
        'Davao Occidental': { Malita: [] },
        'Davao Oriental': { 'Mati City': [] },
    },
    'Region XII - SOCCSKSARGEN': {
        Cotabato: { 'Kidapawan City': [] },
        Sarangani: { Alabel: [] },
        'South Cotabato': { 'Koronadal City': [] },
        'Sultan Kudarat': { Isulan: [] },
    },
    'Region XIII - Caraga': {
        'Agusan del Norte': { 'Cabadbaran City': [] },
        'Agusan del Sur': { Prosperidad: [] },
        'Dinagat Islands': { 'San Jose': [] },
        'Surigao del Norte': { 'Surigao City': [] },
        'Surigao del Sur': { 'Tandag City': [] },
    },
    'BARMM - Bangsamoro Autonomous Region in Muslim Mindanao': {
        Basilan: { 'Isabela City': [] },
        'Lanao del Sur': { 'Marawi City': [] },
        'Maguindanao del Norte': { 'Datu Odin Sinsuat': [] },
        'Maguindanao del Sur': { Buluan: [] },
        Sulu: { Jolo: [] },
        'Tawi-Tawi': { Bongao: [] },
    },
};

export function regions() {
    return Object.keys(addressTree);
}

export function provincesFor(region) {
    return Object.keys(addressTree[region] ?? {});
}

export function citiesFor(region, province) {
    return Object.keys(addressTree[region]?.[province] ?? {});
}

export function barangaysFor(region, province, city) {
    return addressTree[region]?.[province]?.[city] ?? [];
}
