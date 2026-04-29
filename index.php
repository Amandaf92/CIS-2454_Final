<!DOCTYPE html>
<html>
<head>
    <title>Shopping List App</title>
    <link rel="stylesheet" href="css/stylesheet.css">

    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
</head>

<body>
<div id="app"></div>

<script type="text/babel">
const { useState, useEffect } = React;

function App() {
    const [stores, setStores] = useState([]);
    const [selectedStore, setSelectedStore] = useState(null);
    const [items, setItems] = useState([]);
    const [newStore, setNewStore] = useState("");
    const [newItem, setNewItem] = useState("");

    const [editingItemId, setEditingItemId] = useState(null);
    const [editName, setEditName] = useState("");
    const [editQty, setEditQty] = useState(1);

    const fetchStores = async () => {
        const res = await fetch("api/stores.php");
        setStores(await res.json());
    };

    const fetchItems = async (storeId) => {
        const res = await fetch(`api/items.php?store_id=${storeId}`);
        setItems(await res.json());
    };

    useEffect(() => { fetchStores(); }, []);

    const addStore = async () => {
        if (!newStore.trim()) return;

        await fetch("api/stores.php", {
            method: "POST",
            body: JSON.stringify({ name: newStore })
        });

        setNewStore("");
        fetchStores();
    };

    const deleteStore = async (id) => {
        await fetch(`api/stores.php?id=${id}`, { method: "DELETE" });
        setSelectedStore(null);
        fetchStores();
    };

    const addItem = async () => {
        if (!newItem.trim()) return;

        await fetch("api/items.php", {
            method: "POST",
            body: JSON.stringify({
                store_id: selectedStore.id,
                name: newItem,
                quantity: 1
            })
        });

        setNewItem("");
        fetchItems(selectedStore.id);
    };

    const deleteItem = async (id) => {
        await fetch(`api/items.php?id=${id}`, { method: "DELETE" });
        fetchItems(selectedStore.id);
    };

    const toggleItem = async (item) => {
        await fetch("api/items.php", {
            method: "PUT",
            body: JSON.stringify({
                id: item.id,
                checked: Number(item.checked) === 1 ? 0 : 1;
                name: item.name,
                quantity: item.quantity
            })
        });
        fetchItems(selectedStore.id);
    };

    const startEdit = (item) => {
        setEditingItemId(item.id);
        setEditName(item.name);
        setEditQty(item.quantity);
    };

    const saveEdit = async (item) => {
        await fetch("api/items.php", {
            method: "PUT",
            body: JSON.stringify({
                id: item.id,
                checked: item.checked,
                name: editName,
                quantity: editQty
            })
        });

        setEditingItemId(null);
        fetchItems(selectedStore.id);
    };
    
    // Add and List current stores from the Stores table
    return (
        <div className="shoppingContainer">
            <h2>Shopping Lists</h2>

            <div className="addStore">
                <input type="text" value={newStore} onChange={e => setNewStore(e.target.value)} placeholder="New Store" />
                <button className="button addButton" onClick={addStore}> Add </button>
            </div>

            <ul className="storeList">
                {stores.map(store => (
                    <li key={store.id} className="storeRows">
                        <span
                            onClick={() => {
                                setSelectedStore(store);
                                fetchItems(store.id);
                            }}
                            style={{ cursor: "pointer" }}>
                            {store.name}
                        </span>
                        <button className="button deleteButton" onClick={() => deleteStore(store.id)}> Delete </button>
                    </li>
                ))}
            </ul>
            
            {selectedStore && ( // List the Items associated with a Selected Store
                <div className="itemContainer">
                    <h3>{selectedStore.name} Items</h3>
                    <div>
                        <input type="text" value={newItem} onChange={e => setNewItem(e.target.value)} placeholder="New Item"/>
                        <button className="button addButton" onClick={addItem}> Add </button>
                    </div>
                    <ul>
                        {items.map(item => (
                            <li key={item.id} className="storeRows">
                                {editingItemId === item.id ? (
                                    <div>
                                        <input value={editName} onChange={e => setEditName(e.target.value)}/>
                                        <input type="number" value={editQty} onChange={e => setEditQty(e.target.value)}/>
                                        <button className="button saveButton" onClick={() => saveEdit(item)}> Save </button>

                                        <button className="button cancelButton" onClick={() => setEditingItemId(null)}> Cancel </button>
                                    </div>
                                ) : (
                                    <div className="storeRows">
                                        <span 
                                            onClick={() => toggleItem(item)} 
                                            style={{ 
                                                cursor: "pointer",
                                                textDecoration: Number(item.checked) === 1 ? "line-through" : "none"
                                            }}
                                        >
                                            <input
                                                type="checkbox"
                                                checked={Number(item.checked) === 1}
                                                readOnly
                                                style={{ marginRight: "8px" }}
                                            />
                                            {item.name} (x{item.quantity})
                                        </span>
                                        <div className="buttonsRight">
                                            <button className="button editButton" onClick={() => startEdit(item)}> Edit </button>
                                            <button className="button deleteButton" onClick={() => deleteItem(item.id)}> Delete </button>
                                        </div>
                                    </div>
                                )}
                            </li>
                        ))}
                    </ul>
                </div>
            )}
                <footer>
                Amanda Fockler CIS-Final - &copy; - CIS-2454-Winter
                </footer>
        </div>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
</script>
</body>
</html>
