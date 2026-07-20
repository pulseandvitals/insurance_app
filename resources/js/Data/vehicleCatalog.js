// Fixed enums for the Motor CTPL wizard. Brand/Model/Variant are NOT here —
// that catalog is producer-registerable and lives in the database (see
// VehicleCatalogService), since producers grow it by typing new vehicles.

export const vehicleClasses = [
    'Private',
    'Motorcycles',
    'Commercial-Trucks',
    'LTO Tricycle',
    'LTO Taxi',
    'LTO Public Utility Jeepney',
    'LTO Public Utility Bus',
];

export const yearModels = Array.from({ length: 2027 - 1965 + 1 }, (_, i) => 2027 - i);
