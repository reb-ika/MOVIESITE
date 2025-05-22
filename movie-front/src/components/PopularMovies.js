import Image from "next/image";

const PopularMovies = () => {
  return (
    <section className="py-16 bg-gray-800">
      <div className="container mx-auto px-4">
        <h2 className="text-3xl md:text-4xl font-bold text-center text-white mb-12">
          Popular Movies
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
          {Array.from({ length: 8 }).map((_, index) => (
            <div
              key={index}
              className="bg-gray-700 rounded-lg shadow-lg overflow-hidden"
            >
              <Image
                src={`https://picsum.photos/300/400?random=${index + 1}`}
                alt={`Movie ${index + 1}`}
                width={300}
                height={400}
                className="object-cover w-full h-64"
              />
              <div className="p-4">
                <h3 className="text-lg font-bold text-white mb-2">
                  Movie Title {index + 1}
                </h3>
                <p className="text-sm text-gray-400">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <button className="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white text-sm">
                  View Details
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default PopularMovies;