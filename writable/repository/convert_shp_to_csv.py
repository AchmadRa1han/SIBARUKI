import shapefile
import csv
import os

def convert_shp_to_csv(shp_path):
    try:
        # Baca shapefile
        reader = shapefile.Reader(shp_path)
        fields = [field[0] for field in reader.fields][1:] # Lewati DeletionFlag
        
        # Nama file output
        csv_path = os.path.splitext(shp_path)[0] + ".csv"
        
        with open(csv_path, 'w', newline='', encoding='utf-8') as csvfile:
            writer = csv.writer(csvfile)
            # Tambahkan kolom WKT di awal header
            writer.writerow(['WKT'] + fields)
            
            # Loop setiap baris data
            for shape_record in reader.shapeRecords():
                # Ambil data atribut
                record = shape_record.record
                
                # Konversi geometri ke WKT
                shape = shape_record.shape
                wkt = ""
                
                if shape.shapeType == 1: # Point
                    wkt = f"POINT ({shape.points[0][0]} {shape.points[0][1]})"
                elif shape.shapeType == 3: # Polyline
                    coords = ", ".join([f"{p[0]} {p[1]}" for p in shape.points])
                    wkt = f"LINESTRING ({coords})"
                elif shape.shapeType == 5: # Polygon
                    coords = ", ".join([f"{p[0]} {p[1]}" for p in shape.points])
                    wkt = f"POLYGON (({coords}))"
                
                writer.writerow([wkt] + list(record))
        
        print(f"Berhasil: {os.path.basename(shp_path)} -> {os.path.basename(csv_path)}")
    except Exception as e:
        print(f"Gagal mengonversi {shp_path}: {e}")

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    print(f"Memulai pemindaian di: {base_dir}")
    print("-" * 50)
    
    for root, dirs, files in os.walk(base_dir):
        for file in files:
            if file.lower().endswith(".shp"):
                full_path = os.path.join(root, file)
                convert_shp_to_csv(full_path)

if __name__ == "__main__":
    main()
