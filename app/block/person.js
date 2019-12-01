/**
 * BLOCK: react-lifecycle-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */
import Select from 'react-select';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { apiFetch } = wp;
const {
    registerStore,
    withSelect
} = wp.data;

const {
    Spinner
} = wp.components;

const actions = {
    setPersons( persons ) {
        return {
            type: 'SET_PERSONS',
            persons
        }
    },
    receivePersons( path ) {
        return {
            type: 'RECEIVE_PERSONS',
            path
        }
    }
};

const store = registerStore( 'wp-reactivate', {
    reducer( state = { persons: [], selectedPersons: [] }, action ) {

        switch (action.type) {
            case 'SET_PERSONS':
                return {
                    ...state,
                    persons: action.persons,
                };
        }

        return state;
    },

    actions,

    selectors: {
        receivePersons( state ) {
            const { persons } = state;
            return persons;
        }
    },

    controls: {
        RECEIVE_PERSONS( action ) {
            return apiFetch( { path: action.path } )
        }
    },

    resolvers: {
        * receivePersons( state ) {
            const persons = yield actions.receivePersons( '/wp-reactivate/v1/persons/' );
            return actions.setPersons( persons );
        }
    }
} );

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     wpr-person-list/person-container
 * @param  {Object}   settings Block settings.
 * @return {*}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'wrp-person-list/person-container', {
    title: __( 'Person Block' ), // Block title.
    attributes: {
        selectedPersons: {
            type: 'array',
            default: []
        }
    },
    description: __( 'Show Person Information in Block' ),
    icon: 'admin-users', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    keywords: [
        __( 'Person Container' ),
        __( 'Person Lister' ),
        __( 'Person Box' ),
    ],

    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     *
     * The "edit" property must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     *
     * @param {Object} props Props.
     * @returns {*} JSX Component.
     */
    edit: withSelect( ( select ) => {
        return {
            persons: select( 'wp-reactivate' ).receivePersons()
        }
    } )( ( props ) => {
        // Creates a <p class='wp-block-cgb-block-react-lifecycle-block'></p>.
        const persons = props.persons.value;
        const { attributes: { selectedPersons, isModalOpen }, setAttributes } = props;
        console.log( props );
        setAttributes( {
            selectedPersons
        } );

        function setSelectedItems( selectedItems ) {
            if (selectedItems !== null) {
                let selectedPersons = [];
                selectedItems.forEach( ( selectedItem ) => {
                    const selectedPerson = persons.filter( ( person ) => selectedItem.value === person.id );
                    selectedPersons.push( selectedPerson );
                } );

                setAttributes( { selectedPersons } );
            } else {
                setAttributes( { selectedPersons: [] } );
            }
        }

        if (persons) {
            console.log( selectedPersons );
            /*
            Filter empty named persons.
             */
            const filteredPersons = persons.filter( ( person ) => person.first_name[0] !== '' );
            const options = filteredPersons.map( ( person ) => {
                return {
                    value: person.id,
                    label: person.first_name[0]
                }
            } );
            return (
                <div className={ props.className }>
                    <h1>Select Persons</h1>
                    <Select
                        options={ options }
                        isMulti={ true }
                        className="basic-multi-select"
                        classNamePrefix="select"
                        onChange={ ( selectedItems ) => setSelectedItems( selectedItems ) }
                    />
                </div>
            );
        } else {
            return (
                <Spinner/>
            )
        }


    } ),


    save: () => {
        return null
    }
} );
