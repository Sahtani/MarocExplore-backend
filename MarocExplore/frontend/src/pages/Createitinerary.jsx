import { useState, useEffect } from 'react';
import { axiosClient } from "../config/axiosClient";
import getCookie from "../helper/cookie";

const AddItinerary = () => {
    const [title, setTitle] = useState('');
    const [category, setCategory] = useState('');
    const [duration, setDuration] = useState('');
    const [image, setImage] = useState('');  
     const [destinations, setDestinations] = useState([{ name: '', accommodation: '', places: '', activities: '', dishes: '' }]);
    const [categories, setCategories] = useState([]);
    const [error, setError] = useState('');
    const token = getCookie("ACCESS_TOKEN");
    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const response = await axiosClient.get("/categories");
                const categoriesData = response.data.categories.map((category) => ({
                    id: category.id,
                    name: category.name,
                }));
                setCategories(categoriesData);
                console.log(categoriesData);
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        };

        fetchCategories();
    }, []);

    const addDestination = () => {
        setDestinations([...destinations, { name: '', accommodation: '' }]);
    };

    const handleDestinationChange = (index, field, value) => {
        const updatedDestinations = [...destinations];
        updatedDestinations[index][field] = value;
        setDestinations(updatedDestinations);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!token) {
                        navigate("/login"); 
                        return;
                    }
        const formData = new FormData();
        formData.append('title', title);
        formData.append('category_id', category); // Fix here, change 'category' to 'category_id'
        formData.append('duration', duration);
        formData.append('image', image);
        destinations.forEach((destination, index) => {
            formData.append(`destinations[${index}][name]`, destination.name);
            formData.append(`destinations[${index}][accommodation]`, destination.accommodation);
            formData.append(`destinations[${index}][places]`, destination.places);
            formData.append(`destinations[${index}][activities]`, destination.activities);
            formData.append(`destinations[${index}][dishes]`, destination.dishes);
        });
    
        try {
            const response = await axiosClient.post('/store', formData);
    
            if (response.status === 201) {
                alert('Itinerary added successfully!');
                navigate("/home"); 
                // Clear form fields after successful submission
                setTitle('');
                setCategory('');
                setDuration('');
                setImage('');
                setDestinations([{ name: '', accommodation: '', places: '', activities: '', dishes: '' }]);
                setError('');
            } else {
                const data = response.data;
                setError(data.message); // Assuming error message is returned from the server
            }
        } catch (error) {
            console.error('Error:', error);
            setError('An error occurred. Please try again.');
        }
    };
    

    return (
        <div className="max-w-md mx-auto mt-8 p-6 bg-gray-100 rounded-lg">
            <h1 className="text-xl font-semibold mb-4">Add Itinerary</h1>
            {error && <p className="text-red-500 mb-4">{error}</p>}
            <form onSubmit={handleSubmit}>
                <div className="mb-4">
                    <label htmlFor="title" className="block mb-1">Title:</label>
                    <input type="text" id="title" value={title} onChange={(e) => setTitle(e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
                </div>
                <div className="mb-4">
                    <label htmlFor="category" className="block text-gray-700 font-bold mb-2">Category:</label>
                    <select id="category" name="category_id" value={category} onChange={(e) => setCategory(e.target.value)} required className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                        <option value="">Select category</option>
                        {categories.map((category) => (
                            <option key={category.id} value={category.id}>{category.name}</option>
                        ))}
                    </select>
                </div>
                <div className="mb-4">
                    <label htmlFor="duration" className="block mb-1">Duration:</label>
                    <input type="text" id="duration" value={duration} onChange={(e) => setDuration(e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
                </div>
                <div className="mb-4">
                    <label htmlFor="image" className="block mb-1">Image:</label>
                    <input type="file" onChange={(e) => setImage(e.target.files[0])} className="border border-gray-300 rounded-md p-2 w-full" />
                </div>
                <h2 className="text-lg font-semibold mb-2">Destinations</h2>
                {destinations.map((destination, index) => (
    <div key={index} className="mb-4 border border-gray-300 rounded-md p-4">
        <h3 className="text-lg font-semibold mb-2">Destination {index + 1}</h3>
        <div className="mb-2">
            <label htmlFor={`destinationName${index}`} className="block mb-1">Name:</label>
            <input type="text" id={`destinationName${index}`} value={destination.name} onChange={(e) => handleDestinationChange(index, 'name', e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
        </div>
        <div className="mb-2">
            <label htmlFor={`destinationAccommodation${index}`} className="block mb-1">Accommodation:</label>
            <input type="text" id={`destinationAccommodation${index}`} value={destination.accommodation} onChange={(e) => handleDestinationChange(index, 'accommodation', e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
        </div>
        <div className="mb-2">
            <label htmlFor={`destinationPlaces${index}`} className="block mb-1">Places:</label>
            <textarea id={`destinationPlaces${index}`} value={destination.places} onChange={(e) => handleDestinationChange(index, 'places', e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
        </div>
        <div className="mb-2">
            <label htmlFor={`destinationActivities${index}`} className="block mb-1">Activities:</label>
            <textarea id={`destinationActivities${index}`} value={destination.activities} onChange={(e) => handleDestinationChange(index, 'activities', e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
        </div>
        <div className="mb-2">
            <label htmlFor={`destinationDishes${index}`} className="block mb-1">Dishes:</label>
            <textarea id={`destinationDishes${index}`} value={destination.dishes} onChange={(e) => handleDestinationChange(index, 'dishes', e.target.value)} className="border border-gray-300 rounded-md p-2 w-full" />
        </div>
    </div>
))}

                <button type="button" onClick={addDestination} className="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">Add Destination</button>
                <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded-md">Submit</button>
            </form>
        </div>
    );
};

export default AddItinerary;




// import { useState, useEffect } from "react";
// import { useNavigate } from "react-router-dom";
// import { axiosClient } from "../config/axiosClient";
// import getCookie from "../helper/cookie";

// export default function CreateItinerary() {
//     const [formData, setFormData] = useState({
//         title: "",
//         duration: "",
//         image: null,
//         category_id: ""
//     });
//     const [destinations, setDestinations] = useState([
//         { name: "", accommodation: "", places: "", activities: "", dishes: "" }
//     ]); // Initialize destinations state with an empty array containing a default destination object
//     const [categories, setCategories] = useState([]);
//     const [errors, setErrors] = useState(null);
//     const navigate = useNavigate();
//     const token = getCookie("ACCESS_TOKEN");

//     const handleChange = (e) => {
//         const { name, value } = e.target;
//         setFormData({ ...formData, [name]: value });
//         setErrors({ ...errors, [name]: "" });
//     };

//     const handleFileChange = (e) => {
//         const file = e.target.files[0];
//         setFormData({ ...formData, image: file });
//         setErrors({ ...errors, image: "" });
//     };

//     const handleSubmit = async (e) => {
//         e.preventDefault();
//         if (!token) {
//             navigate("/login"); // Redirect to login page if user is not authenticated
//             return;
//         }
//         const formDataToSend = new FormData();
//         formDataToSend.append("title", formData.title);
//         formDataToSend.append("duration", formData.duration);
//         formDataToSend.append("image", formData.image);
//         formDataToSend.append("category_id", formData.category_id);
//         formDataToSend.append("destinations", JSON.stringify(destinations)); // Convert destinations array to JSON string before sending
//         try {
//             await axiosClient.post("/store", formDataToSend, {
//                 headers: {
//                     "Content-Type": "multipart/form-data",
//                     Authorization: `Bearer ${token}`
//                 }
//             });
//             navigate("/"); // Redirect to home page after successful submission
//         } catch (error) {
//             if (error.response) {
//                 setErrors(error.response.data);
//             } else {
//                 console.error("Error creating itinerary:", error);
//             }
//         }
//     };

//     useEffect(() => {
//         const fetchCategories = async () => {
//             try {
//                 const response = await axiosClient.get("/categories");
//                 const categoriesData = response.data.categories.map((category) => ({
//                     id: category.id,
//                     name: category.name,
//                 }));
//                 setCategories(categoriesData);
//             } catch (error) {
//                 console.error("Error fetching categories:", error);
//             }
//         };

//         fetchCategories();
//     }, []);

//     const handleAddDestination = () => {
//         setDestinations([...destinations, { name: "", accommodation: "", places: "", activities: "", dishes: "" }]);
//     };

//     const handleRemoveDestination = (index) => {
//         const updatedDestinations = [...destinations];
//         updatedDestinations.splice(index, 1);
//         setDestinations(updatedDestinations);
//     };
//     return (
//         <>
//             <form onSubmit={handleSubmit} className="max-w-md mx-auto p-6 bg-white shadow-md rounded-md">
//                 <h2 className="text-xl font-bold mb-6">Create Itinerary</h2>
//                 <div className="mb-4">
//                     <label htmlFor="title" className="block text-gray-700 font-bold mb-2">Title:</label>
//                     <input type="text" id="title" name="title" value={formData.title} onChange={handleChange} required className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter title" />
//                 </div>

//                 <div className="mb-4">
//                     <label htmlFor="duration" className="block text-gray-700 font-bold mb-2">Duration (in days):</label>
//                     <input type="number" id="duration" name="duration" value={formData.duration} onChange={handleChange} required className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter duration" />
//                 </div>

//                 <div className="mb-4">
//                     <label htmlFor="image" className="block text-gray-700 font-bold mb-2">Image:</label>
//                     <input type="file" id="image" name="image" onChange={handleFileChange} className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" />
//                 </div>

//                 <div className="mb-4">
//                     <label htmlFor="category" className="block text-gray-700 font-bold mb-2">Category:</label>
//                     <select id="category" name="category_id" value={formData.category_id} onChange={handleChange} required className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
//                         <option value="">Select category</option>
//                         {categories.map((category) => (
//                             <option key={category.id} value={category.id}>{category.name}</option>
//                         ))}
//                     </select>
//                 </div>

//                 {destinations.map((destination, index) => (
//                     <div key={index} className="mb-4 border rounded-md p-4">
//                         <h3 className="font-semibold mb-2">Destination {index + 1}</h3>
//                         <div className="mb-2">
//                             <label htmlFor={`name${index}`} className="block text-gray-700 font-bold mb-1">Name:</label>
//                             <input type="text" id={`name${index}`} name="name" value={destination.name} onChange={(e) => handleChange(e, index)} className="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" />
//                         </div>
//                         <button type="button" onClick={() => handleRemoveDestination(index)} className="bg-red-500 text-white px-4 py-2 rounded-md mr-2">Remove</button>
//                     </div>
//                 ))}
//                 <button type="button" onClick={handleAddDestination} className="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Add Destination</button>
//                 <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
//             </form>
//             {errors && (
//         <div className="text-red-500">
//             {typeof errors === "object" && errors.image && <p>{errors.image}</p>}
//         </div>
//     )}

//         </>
//     );
// }


// import  { useState, useEffect } from "react";
// import { useNavigate } from "react-router-dom";
// import { axiosClient } from "../config/axiosClient";
// import getCookie from "../helper/cookie";

// export default function CreateItinerary() {
//     const [formData, setFormData] = useState({
//         title: "",
//         duration: "",
//         image: null,
//         category_id: ""
//     });
//     const [destinations, setDestinations] = useState([
//         { name: "", accommodation: "", places: "", activities: "", dishes: "" }
//     ]);
//     const [categories, setCategories] = useState([]);
//     const [errors, setErrors] = useState(null);
//     const navigate = useNavigate();
//     const token = getCookie("ACCESS_TOKEN");

//     const handleChange = (e, index) => {
//         const { name, value } = e.target;
//         if (typeof index !== "undefined") {
//             const updatedDestinations = destinations.map((destination, i) => {
//                 if (i === index) {
//                     return { ...destination, [name]: value };
//                 }
//                 return destination;
//             });
//             setDestinations(updatedDestinations);
//         } else {
//             console.error("Invalid index undefined");
//         }
//     };
    
    
    

//     const handleAddDestination = () => {
//         setDestinations([...destinations, { name: "", accommodation: "", places: "", activities: "", dishes: "" }]);
//     };

//     const handleRemoveDestination = (index) => {
//         const updatedDestinations = [...destinations];
//         updatedDestinations.splice(index, 1);
//         setDestinations(updatedDestinations);
//     };

//     const handleFileChange = (e) => {
//         const file = e.target.files[0];
//         setFormData({ ...formData, image: file });
//         setErrors({ ...errors, image: "" });
//     };

//     const handleSubmit = async (e) => {
//         e.preventDefault();
//         if (!token) {
//             navigate("/login");
//             return;
//         }
//         const formDataToSend = new FormData();
//         formDataToSend.append("title", formData.title);
//         formDataToSend.append("duration", formData.duration);
//         formDataToSend.append("image", formData.image);
//         formDataToSend.append("category_id", formData.category_id);
//         formDataToSend.append("destinations", JSON.stringify(destinations));
//         try {
//             await axiosClient.post("/store", formDataToSend, {
//                 headers: {
//                     "Content-Type": "multipart/form-data",
//                     Authorization: `Bearer ${token}`
//                 }
//             });
//             navigate("/");
//         } catch (error) {
//             if (error.response) {
//                 setErrors(error.response.data);
//             } else {
//                 console.error("Error creating itinerary:", error);
//             }
//         }
//     };

//     useEffect(() => {
//         const fetchCategories = async () => {
//             try {
//                 const response = await axiosClient.get("/categories");
//                 const categoriesData = response.data.categories.map((category) => ({
//                     id: category.id,
//                     name: category.name,
//                 }));
//                 setCategories(categoriesData);
//             } catch (error) {
//                 console.error("Error fetching categories:", error);
//             }
//         };

//         fetchCategories();
//     }, []);