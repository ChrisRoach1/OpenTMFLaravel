type MetadataType = {
    id: number;
    display: string;
};

type DocumentMetadata = {
    id: number;
    title: string;
    metadata_type_id: number;
    metadata_type: MetadataType;
    options: string[];
    is_required: boolean;
};