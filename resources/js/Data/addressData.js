// Representative Philippine Region -> Province -> City/Municipality -> Barangay tree
// for cascading address selects. Full LTO/PSA coverage is thousands of entries;
// this subset covers the major regions with real names to demonstrate the cascade.

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
    'Region III - Central Luzon': {
        Pampanga: {
            'San Fernando': ['Dolores', 'San Jose', 'Sto. Niño'],
            Angeles: ['Balibago', 'Malabanias'],
        },
        Bulacan: {
            Malolos: ['Sto. Rosario', 'Barihan'],
             'San Jose del Monte': ['Tungkong Mangga', 'Muzon'],
        },
    },
    'Region IV-A - CALABARZON': {
        Cavite: {
            'Dasmariñas': ['Zone I', 'Salawag', 'San Agustin'],
            'Bacoor': ['Molino', 'Zapote'],
        },
        Laguna: {
            'Santa Rosa': ['Balibago', 'Tagapo'],
            'Calamba': ['Real', 'Canlubang'],
        },
        Batangas: {
            'Batangas City': ['Poblacion', 'Alangilan'],
            Lipa: ['Marauoy', 'Balintawak'],
        },
    },
    'Region VII - Central Visayas': {
        Cebu: {
            'Cebu City': ['Lahug', 'Guadalupe', 'Talamban'],
            Mandaue: ['Centro', 'Tipolo'],
        },
        Bohol: {
            Tagbilaran: ['Poblacion I', 'Poblacion II'],
        },
    },
    'Region XI - Davao Region': {
        'Davao del Sur': {
            'Davao City': ['Buhangin', 'Talomo', 'Poblacion'],
        },
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
