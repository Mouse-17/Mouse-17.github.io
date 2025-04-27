export interface Yard {
    id: number;
    Ten_san: string;
    Mo_ta: string;
    Dia_chi: string;
    Hinh_anh: string;
    hot: number;
    view: number;
    So_luong: number;
    ID_Loai: number;
    Trang_thai: number;
    diem_danh_gia?: number;
    tong_danh_gia?: number;
    yard?: {
        id: number;
        Ten_san: string;
    };
}