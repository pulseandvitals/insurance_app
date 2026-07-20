// Representative vehicle catalog for cascading Brand -> Model -> Variant selects.
// Keyed by Vehicle Class, since LTO CTPL brand lists differ substantially between
// private/commercial vehicles and motorcycles.

export const vehicleCatalog = {
    Private: {
        Toyota: {
            Vios: ['1.3 XE CVT', '1.3 XLE CVT', '1.5 G CVT'],
            Fortuner: ['2.4 G 4x2 AT', '2.8 V 4x4 AT'],
            Innova: ['2.0 J MT', '2.0 G AT', '2.8 V AT'],
            Wigo: ['1.0 G AT', '1.0 TRD S AT'],
        },
        Mitsubishi: {
            Mirage: ['GLX MT', 'GLS CVT'],
            'Mirage G4': ['GLX MT', 'GLS CVT'],
            Xpander: ['GLS AT', 'GLS Plus AT'],
            Montero: ['GLS AT', 'GT 4WD AT'],
        },
        Honda: {
            City: ['S MT', 'V CVT', 'RS Turbo'],
            Civic: ['S CVT', 'RS Turbo'],
            'CR-V': ['S AT', 'V AT'],
        },
        Ford: {
            Ranger: ['XLS 4x2 MT', 'XLT 4x2 AT', 'Wildtrak 4x4 AT'],
            EcoSport: ['Ambiente MT', 'Titanium AT'],
        },
        Nissan: {
            Almera: ['1.0 VL Turbo', '1.5 VE'],
            Navara: ['EL 4x2 AT', 'VL 4x4 AT'],
        },
    },
    Motorcycles: {
        Honda: {
            'Click 125i': ['Standard', 'Sport'],
            'Beat': ['Standard', 'Street'],
            'TMX 125': ['Alpha'],
            'XRM 125': ['DS'],
        },
        Yamaha: {
            Mio: ['Sporty', 'i125'],
            Aerox: ['155 Standard', '155 VVA'],
            NMAX: ['155 Standard'],
        },
        Suzuki: {
            Raider: ['J Fi 150'],
            Skydrive: ['Sport'],
        },
        Kawasaki: {
            Barako: ['II'],
            Rouser: ['NS160', 'RS200'],
        },
    },
    'Commercial-Trucks': {
        Isuzu: {
            'Elf NHR': ['Dropside', 'Aluminum Van'],
            'Elf NKR': ['Dropside', 'Aluminum Van'],
            Giga: ['Cargo Truck'],
        },
        Hino: {
            '300 Series': ['Dropside', 'Aluminum Van'],
            '500 Series': ['Cargo Truck'],
        },
        Fuso: {
            Canter: ['Dropside', 'Aluminum Van'],
        },
    },
    'LTO Tricycle': {
        Bajaj: { RE: ['Standard'] },
        Kawasaki: { 'Fastwind': ['Standard'] },
    },
    'LTO Taxi': {
        Toyota: { Vios: ['1.3 Base MT'] },
        Hyundai: { Accent: ['1.4 GL MT'] },
    },
    'LTO Public Utility Jeepney': {
        Isuzu: { Traviz: ['Standard'] },
        'Almazora': { 'E-Jeepney': ['Standard'] },
    },
    'LTO Public Utility Bus': {
        Hino: { 'AK Series': ['City Bus'] },
        Isuzu: { LT134: ['Provincial Bus'] },
    },
};

export const vehicleClasses = [
    'Private',
    'Motorcycles',
    'Commercial-Trucks',
    'LTO Tricycle',
    'LTO Taxi',
    'LTO Public Utility Jeepney',
    'LTO Public Utility Bus',
];

export function brandsFor(vehicleClass) {
    return Object.keys(vehicleCatalog[vehicleClass] ?? {});
}

export function modelsFor(vehicleClass, brand) {
    return Object.keys(vehicleCatalog[vehicleClass]?.[brand] ?? {});
}

export function variantsFor(vehicleClass, brand, model) {
    return vehicleCatalog[vehicleClass]?.[brand]?.[model] ?? [];
}

export const yearModels = Array.from({ length: 2027 - 1965 + 1 }, (_, i) => 2027 - i);
