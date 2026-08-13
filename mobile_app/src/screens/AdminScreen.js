import React, { useContext } from 'react';
import { View, StyleSheet, ActivityIndicator, Text } from 'react-native';
import { WebView } from 'react-native-webview';
import { AuthContext } from '../context/AuthContext';

// Import cấu hình API để lấy IP gốc thay vì localhost
import apiClient from '../api/config';

export default function AdminScreen() {
  const { user } = useContext(AuthContext);

  // Chỉ cho phép admin truy cập
  if (!user || user.vaitro !== 1) {
    return (
      <View style={styles.center}>
        <Text style={styles.errorText}>Bạn không có quyền truy cập trang này!</Text>
      </View>
    );
  }

  // Lấy baseUrl từ apiClient.defaults.baseURL (ví dụ: http://192.168.77.100:8080/banhang/api)
  const baseUrl = apiClient.defaults.baseURL;
  const adminBaseUrl = baseUrl.replace(/\/api\/?$/, '/admin');
  
  // Custom Base64 Encode cho React Native
  const encodeBase64 = (str) => {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    let output = '';
    for (let block = 0, charCode, i = 0, map = chars; str.charAt(i | 0) || (map = '=', i % 1); output += map.charAt(63 & block >> 8 - i % 1 * 8)) {
      charCode = str.charCodeAt(i += 3/4);
      if (charCode > 0xFF) { throw new Error("'btoa' failed"); }
      block = block << 8 | charCode;
    }
    return output;
  };

  const mobileAuth = encodeBase64(user.email);
  const adminUrl = `${adminBaseUrl}?mobile_auth=${mobileAuth}`;

  return (
    <View style={styles.container}>
      <WebView 
        source={{ uri: adminUrl }} 
        startInLoadingState={true}
        renderLoading={() => (
          <ActivityIndicator color="#0f172a" size="large" style={styles.loading} />
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20
  },
  errorText: {
    fontSize: 18,
    color: 'red',
    fontWeight: 'bold',
    textAlign: 'center'
  },
  loading: {
    position: 'absolute',
    top: '50%',
    left: '50%',
    marginLeft: -20,
    marginTop: -20,
  }
});
