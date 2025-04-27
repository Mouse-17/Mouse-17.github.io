export interface Product {
    id: number;
    Ten_san_pham: string;
    Gia: number;
    Mo_ta: string;
    Anh_dai_dien: string;
    hot: number;
    view: number;
    bestseller: number;
    so_luong: number;
    trang_thai: number;
    id_thuonghieu: number;
    id_danhmuc: number;
    anh_phu?: string;
    gia_giam?: number;
    diem_danh_gia?: number;
    tong_danh_gia?: number;
    mau_sac?: string;
    kich_thuoc?: string;
    danh_muc?: {
        id: number;
        ten_danh_muc: string;
    };
    thuong_hieu?: {
        id: number;
        ten_thuong_hieu: string;
    };
}