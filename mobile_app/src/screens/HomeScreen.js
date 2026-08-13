import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Image, StyleSheet, TouchableOpacity, ActivityIndicator, ScrollView } from 'react-native';
import apiClient from '../api/config';

export default function HomeScreen({ navigation }) {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCat, setSelectedCat] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const resCat = await apiClient.get('/categories');
      if (resCat.data.status === 'success') {
        setCategories(resCat.data.data);
      }

      const response = await apiClient.get('/products');
      if (response.data.status === 'success') {
        setProducts(response.data.data);
      }
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const filteredProducts = selectedCat 
    ? products.filter(p => p.id_loai === selectedCat)
    : products;

  const renderItem = ({ item }) => (
    <TouchableOpacity 
      style={styles.card} 
      onPress={() => navigation.navigate('ProductDetail', { id: item.id_sp })}
    >
      <Image source={{ uri: item.hinh_url }} style={styles.image} resizeMode="contain" />
      <Text style={styles.name} numberOfLines={2}>{item.ten_sp}</Text>
      <View style={styles.priceContainer}>
        {item.gia_km > 0 ? (
          <>
            <Text style={styles.price}>{new Intl.NumberFormat('vi-VN').format(item.gia_km)}đ</Text>
            <Text style={styles.oldPrice}>{new Intl.NumberFormat('vi-VN').format(item.gia)}đ</Text>
          </>
        ) : (
          <Text style={styles.price}>{new Intl.NumberFormat('vi-VN').format(item.gia)}đ</Text>
        )}
      </View>
    </TouchableOpacity>
  );

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View>
        <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.catContainer}>
          <TouchableOpacity 
            style={[styles.catBadge, selectedCat === null && styles.catBadgeActive]}
            onPress={() => setSelectedCat(null)}
          >
            <Text style={[styles.catText, selectedCat === null && styles.catTextActive]}>Tất cả</Text>
          </TouchableOpacity>
          {categories.map(cat => (
            <TouchableOpacity 
              key={cat.id_loai} 
              style={[styles.catBadge, selectedCat === cat.id_loai && styles.catBadgeActive]}
              onPress={() => setSelectedCat(cat.id_loai)}
            >
              <Text style={[styles.catText, selectedCat === cat.id_loai && styles.catTextActive]}>{cat.ten_loai}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      <FlatList
        data={filteredProducts}
        keyExtractor={(item) => item.id_sp.toString()}
        renderItem={renderItem}
        numColumns={2}
        contentContainerStyle={styles.list}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  list: { padding: 10 },
  card: {
    flex: 1,
    margin: 8,
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 10,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 5,
    elevation: 3,
  },
  image: { width: '100%', height: 120, marginBottom: 10 },
  name: { fontSize: 14, fontWeight: 'bold', color: '#0f172a', marginBottom: 5, minHeight: 40 },
  priceContainer: { flexDirection: 'row', alignItems: 'center', gap: 5 },
  price: { fontSize: 14, fontWeight: 'bold', color: '#dc2626' },
  oldPrice: { fontSize: 12, color: '#94a3b8', textDecorationLine: 'line-through' },
  catContainer: { paddingHorizontal: 10, paddingVertical: 15, backgroundColor: '#fff', borderBottomWidth: 1, borderColor: '#eee' },
  catBadge: { paddingHorizontal: 15, paddingVertical: 8, backgroundColor: '#f1f5f9', borderRadius: 20, marginRight: 10 },
  catBadgeActive: { backgroundColor: '#0f172a' },
  catText: { color: '#64748b', fontWeight: 'bold' },
  catTextActive: { color: '#fff' }
});
