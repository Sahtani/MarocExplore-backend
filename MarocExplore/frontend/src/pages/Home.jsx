import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { axiosClient } from "../config/axiosClient";
import getCookie from "../helper/cookie";

export default function Home() {

  const [itineraries, setItineraries] = useState([]);
  const [searchQuery, setSearchQuery] = useState("");

  const [categoryFilter, setCategoryFilter] = useState("");
  const [durationFilter, setDurationFilter] = useState("");

  const [selectedCategory, setSelectedCategory] = useState("");

  const [categories, setCategories] = useState([]);
  const token = getCookie('ACCESS_TOKEN');

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await axiosClient.get("/categories");
        setCategories(response.data.categories);
      } catch (error) {
        console.log("Error fetching categories:", error);
      }
    };

    fetchCategories();
  }, []);


  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axiosClient.get("/itineraries");
        setItineraries(response.data.itineraries);
      } catch (error) {
        console.log("Error fetching itineraries:", error);
      }
    };

    fetchData();
  }, []);

  // Fonction pour formater la date
  const formatDate = (dateString) => {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', options);
  };

  const handleSearchChange = (e) => {
    setSearchQuery(e.target.value);
  };

  const handleSearch = async () => {
    try {

      if (searchQuery.trim() === "") {

        const response = await axiosClient.get("/itineraries");
        setItineraries(response.data);
      } else {
        const response = await axiosClient.get("/search", {

          params: {
            title: searchQuery,
          },

        });
        setItineraries(response.data);
        console.log(response.data);
      }

      // console.log(response.data) 
    } catch (error) {
      console.error("Error searching itineraires:", error);
    }
  };
  const handleCategoryFilterChange = (e) => {
    setCategoryFilter(e.target.value);
  };

  const handleDurationFilterChange = (e) => {
    setDurationFilter(e.target.value);
  };
  return (
    <>
      {token && (
        <div className="mt-8 flex items-center justify-center">
          <Link to="/createItinerary" className="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Create Itinerary
          </Link>
        </div>
      )}
      <div className="w-full flex items-center justify-center ">
        <div className="text-center  w-1/6  ">
          <input
            type="text"
            id="search"
            name="search"
            value={searchQuery}
            onChange={handleSearchChange}
            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="search by title"
            required=""
          />
        </div>

        <button
          onClick={handleSearch}
          className="mt-7 px-4 py-2 bg-blue-500 text-white h-fit rounded-md hover:bg-blue-600 focus:outline-none"
        >
          Search
        </button>
      </div>
      <div className="ml-4">
          <label htmlFor="category" className="block font-medium text-gray-700">Category:</label>
          <select
            id="category"
            name="category"
            value={selectedCategory}
            onChange={handleCategoryFilterChange}
            className="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
          >
            <option value="">All</option>
            {categories.map(category => (
              <option key={category.id} value={category.id}>{category.name}</option>
            ))}
          </select>
        </div>
        <div className="ml-4">
  <label htmlFor="duration" className="block font-medium text-gray-700">Duration (days):</label>
  <input
    type="number"
    id="duration"
    name="duration"
    value={selectedDuration}
    onChange={handleDurationChange}
    className="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
    placeholder="Enter duration"
  />
</div>

      <div className="max-w-7xl mx-auto">
        <div className="text-center">
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-16 max-md:max-w-lg mx-auto">
          {itineraries && itineraries.map((itinerary, index) => (
            <div key={index} className="bg-white cursor-pointer rounded overflow-hidden shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] relative top-0 hover:-top-2 transition-all duration-300">
              <img src={`http://127.0.0.1:8000/storage/images/${itinerary.image}`} alt={`Blog Post ${index}`} className="w-full h-60 object-cover" />
              <div className="p-6">
                <span className="text-sm block text-gray-400 mb-2">{formatDate(itinerary.created_at)} | BY {itinerary.user.name}</span>
                <h3 className="text-xl font-bold text-[#333]">{itinerary.title}</h3>
                <p className="text-gray-900 text-sm mb-4">Category: {itinerary.category.name}</p>
                <h4 className="text-lg font-semibold mb-2">Destinations:</h4>
                <ul className="list-disc pl-5 mb-4">
                  {itinerary.destinations.map((destination, index) => (
                    <div key={index} className="mb-4 border border-gray-300 rounded-md p-4">
                      <h4 className="text-lg font-semibold mb-2">Destination {index + 1}</h4>
                      <div className="mb-2">
                        <label htmlFor={`destinationName${index}`} className="block mb-1">Name:</label>
                        <p className="text-gray-900">{destination.name}</p>
                      </div>
                      <div className="mb-2">
                        <label htmlFor={`destinationAccommodation${index}`} className="block mb-1">Accommodation:</label>
                        <p className="text-gray-900">{destination.accommodation}</p>
                      </div>
                      <div className="mb-2">
                        <label htmlFor={`destinationPlaces${index}`} className="block mb-1">Places:</label>
                        <p className="text-gray-900">{destination.places}</p>
                      </div>
                      <div className="mb-2">
                        <label htmlFor={`destinationActivities${index}`} className="block mb-1">Activities:</label>
                        <p className="text-gray-900">{destination.activities}</p>
                      </div>
                      <div className="mb-2">
                        <label htmlFor={`destinationDishes${index}`} className="block mb-1">Dishes:</label>
                        <p className="text-gray-900">{destination.dishes}</p>
                      </div>
                    </div>
                  ))}

                </ul>
                <hr className="my-6" />
                <div className="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style={{ fill: "rgba(0, 0, 0, 1)" }}>
                    <path d="M21 20V6c0-1.103-.897-2-2-2h-2V2h-2v2H9V2H7v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2zM9 18H7v-2h2v2zm0-4H7v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm2-5H5V7h14v2z"></path>
                  </svg>
                  <p className="text-gray-900 text-sm">{itinerary.duration}</p>
                </div>
              </div>
            </div>
          ))}

        </div>
      </div >
    </>

  );
}