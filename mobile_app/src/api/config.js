import axios from 'axios';

// Sử dụng 10.0.2.2 cho Android Emulator để kết nối đến localhost của máy tính
// Nếu bạn chạy trên thiết bị thật, hãy thay bằng IP LAN của máy tính (ví dụ: 192.168.1.15:8080)
// Nếu dùng máy ảo Android (Emulator), hãy đổi lại thành 10.0.2.2:8080
export const API_URL = 'https://laptopvui-2.onrender.com/api';

const apiClient = axios.create({
  baseURL: API_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

export default apiClient;
