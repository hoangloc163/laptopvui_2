import React, { useContext } from 'react';
import { View, Button, StatusBar, TouchableOpacity, Text, StyleSheet } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { CartProvider } from './src/context/CartContext';
import { AuthProvider, AuthContext } from './src/context/AuthContext';
import HomeScreen from './src/screens/HomeScreen';
import ProductDetailScreen from './src/screens/ProductDetailScreen';
import CartScreen from './src/screens/CartScreen';
import CheckoutScreen from './src/screens/CheckoutScreen';
import LoginScreen from './src/screens/LoginScreen';
import RegisterScreen from './src/screens/RegisterScreen';
import AdminScreen from './src/screens/AdminScreen';

const Stack = createNativeStackNavigator();

function AppNavigator() {
  const { user, logout } = useContext(AuthContext);

  return (
    <NavigationContainer>
      <StatusBar barStyle="light-content" backgroundColor="#0f172a" />
      <Stack.Navigator 
        initialRouteName="Home"
        screenOptions={({ navigation }) => ({
          headerStyle: { backgroundColor: '#0f172a' },
          headerTintColor: '#fff',
          headerTitleStyle: { fontWeight: 'bold' },
          headerRight: () => (
            <View style={styles.headerRight}>
              {user ? (
                <>
                  {user.vaitro === 1 && (
                    <TouchableOpacity style={styles.btn} onPress={() => navigation.navigate('Admin')}>
                      <Text style={styles.btnText}>Admin</Text>
                    </TouchableOpacity>
                  )}
                  <TouchableOpacity style={styles.btn} onPress={logout}>
                    <Text style={styles.btnText}>Thoát</Text>
                  </TouchableOpacity>
                </>
              ) : (
                <TouchableOpacity style={styles.btn} onPress={() => navigation.navigate('Login')}>
                  <Text style={styles.btnText}>Đăng nhập</Text>
                </TouchableOpacity>
              )}
              <TouchableOpacity style={styles.cartBtn} onPress={() => navigation.navigate('Cart')}>
                <Text style={styles.btnText}>Giỏ hàng</Text>
              </TouchableOpacity>
            </View>
          ),
        })}
      >
        <Stack.Screen name="Home" component={HomeScreen} options={{ title: 'Laptop Vui' }} />
        <Stack.Screen name="ProductDetail" component={ProductDetailScreen} options={{ title: 'Chi tiết sản phẩm' }} />
        <Stack.Screen name="Cart" component={CartScreen} options={{ title: 'Giỏ hàng' }} />
        <Stack.Screen name="Checkout" component={CheckoutScreen} options={{ title: 'Thanh toán' }} />
        <Stack.Screen name="Login" component={LoginScreen} options={{ title: 'Đăng nhập' }} />
        <Stack.Screen name="Register" component={RegisterScreen} options={{ title: 'Đăng ký' }} />
        <Stack.Screen name="Admin" component={AdminScreen} options={{ title: 'Quản trị hệ thống' }} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <CartProvider>
        <AppNavigator />
      </CartProvider>
    </AuthProvider>
  );
}

const styles = StyleSheet.create({
  headerRight: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10
  },
  btn: {
    paddingHorizontal: 10,
    paddingVertical: 5,
    backgroundColor: '#334155',
    borderRadius: 5
  },
  cartBtn: {
    paddingHorizontal: 10,
    paddingVertical: 5,
    backgroundColor: '#f59e0b',
    borderRadius: 5
  },
  btnText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 12
  }
});
