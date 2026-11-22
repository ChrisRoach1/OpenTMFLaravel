import type { Country } from '@/components/countryconfiguration/types';

export type Site = {
    id: number;
    name: string;
    primary_investigator: string | null;
    country_id: number;
    country?: Country | null;
    created_at: string | null;
    updated_at: string | null;
};
