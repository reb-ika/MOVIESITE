import Header from "../components/Header";
import Footer from "../components/Footer";
import HeroSection from "../components/HeroSection";
import PopularMovies from "../components/PopularMovies";
import Reviews from "../components/Reviews";

export default function Home() {
  return (
    <div>
      <Header />
      <HeroSection />
      <PopularMovies />
      <Reviews />
      <Footer />
    </div>
  );
}