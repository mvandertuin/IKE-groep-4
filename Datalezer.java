import java.util.*;
import java.io.*;
import java.sql.*;

public class Datalezer {

	public static void main(String[] args) {
		Connection connection = null;
		try {
			// Load the JDBC driver
			String driverName = "com.mysql.jdbc.Driver"; // MySQL MM JDBC driver
			Class.forName(driverName).newInstance();

			// Database connectie
			String url = "jdbc:mysql://localhost/ike_yahoo";
			String username = "ike_yahoo";
			String password = "";
			connection = DriverManager.getConnection(url, username, password);
			
			Statement stmt = connection.createStatement();
			/*
			// Lezen van genre hierarchie + in database stoppen		
			File genres = new File(
					"C:/Users/Marieke van der Tuin/Documents/TI2ejaar/Project IKE/ydata-ymusic-user-song-ratings-meta-v1_0/genre-hierarchy.txt");
			Scanner sc = new Scanner(new FileReader(genres));
			while (sc.hasNextLine()) {
				int id = sc.nextInt();
				sc.nextInt(); // parent genre id 
				sc.nextInt(); // level
				String naam = sc.nextLine().replaceAll("'", "\\\\'").replaceAll("\t", "");
				stmt.executeUpdate("INSERT INTO genres VALUES ('"+id+"','"+naam+"', '0', '0')");
			}
			
			// Lezen van song attributes 
			File songs = new File(
					"C:/Users/Marieke van der Tuin/Documents/TI2ejaar/Project IKE/ydata-ymusic-user-song-ratings-meta-v1_0/song-attributes.txt");
			Scanner sc2 = new Scanner(new FileReader(songs));
			while (sc2.hasNextLine()) {
				int id = sc2.nextInt();
				sc2.nextInt(); // album id 
				sc2.nextInt(); // artist id
				int genre = sc2.nextInt();
				stmt.executeUpdate("INSERT INTO songs VALUES ('"+id+"','"+genre+"', '0')");
			}
			*/
			// Lezen van rating data
			
			// train_1 wordt ingelezen => 0 & 2 t/m 9 nog te gaan
			File ratings = new File(
					"C:/Users/Marieke van der Tuin/Documents/TI2ejaar/Project IKE/ydata-ymusic-user-song-ratings-meta-v1_0/train_2.txt");
			Scanner sc3 = new Scanner(new FileReader(ratings));
			while (sc3.hasNextLine()) {
				sc3.nextInt(); // user id 
				int songid = sc3.nextInt();
				int rating = Integer.parseInt(sc3.nextLine().replaceAll("\t",""));
				
				stmt.executeQuery("SELECT * FROM genres WHERE genre_id = (SELECT genre_id FROM songs WHERE song_id = "+songid+")");
				
				ResultSet rs = stmt.getResultSet();
				int aantal_songs = 0;
				int genre_id = -1;
				while (rs.next()) {
					aantal_songs = rs.getInt("songs_count") + 1;
					rating = rs.getInt("votes_count") + rating;
					genre_id = rs.getInt("genre_id");
				}
				stmt.executeUpdate("UPDATE genres SET songs_count="+aantal_songs+", votes_count="+rating+" WHERE genre_id = "+genre_id);
				
			}
			

		} catch (ClassNotFoundException e) {
			System.out.println("driver ontbreekt");
			// Could not find the database driver
		} catch (SQLException e) {
			e.printStackTrace();
			System.out.println(e.getMessage());
			System.out.println("sql fout");
			// Could not connect to the database
		} catch (Exception e) {
			System.out.println("anders");
			e.printStackTrace();
		}

	}
}