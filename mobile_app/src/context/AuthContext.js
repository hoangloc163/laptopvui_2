import React, { createContext, useState, useEffect } from 'react';
import * as SecureStore from 'expo-secure-store';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  // Khôi phục phiên đăng nhập khi mở app
  useEffect(() => {
    const bootstrapAsync = async () => {
      try {
        const userJson = await SecureStore.getItemAsync('user');
        if (userJson) {
          setUser(JSON.parse(userJson));
        }
      } catch (e) {
        console.error("Lỗi khi đọc token đăng nhập", e);
      }
      setIsLoading(false);
    };
    bootstrapAsync();
  }, []);

  const login = async (userData) => {
    setUser(userData);
    await SecureStore.setItemAsync('user', JSON.stringify(userData));
  };

  const logout = async () => {
    setUser(null);
    await SecureStore.deleteItemAsync('user');
  };

  return (
    <AuthContext.Provider value={{ user, isLoading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
