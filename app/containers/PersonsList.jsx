import React, { Component, Fragment } from 'react';
import { Button, Card, Grid, Header, Icon, Image, Message, Modal } from 'semantic-ui-react';

export default class PersonsList extends Component {
    constructor( props ) {
        super( props );

        this.state = {
            firstName: '',
            lastName: '',
            imageOfPerson: '',
            positionInCompany: '',
            description: '',
            socialLinks: {
                github: '',
                linkedin: '',
                xing: '',
                facebook: ''
            },
            isModalOpen: false,
            closeOnEscape: true,
            closeOnDimmerClick: true
        };

        console.log( this.props );
    }

    checkValidUrl = ( str ) => {
        const pattern = new RegExp( '^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i' ); // fragment locator
        return !!pattern.test( str );
    };

    openPersonModal = ( person ) => {
        this.setState( {
            firstName: person[0].first_name && person[0].first_name[0],
            lastName: person[0].last_name && person[0].last_name[0],
            imageOfPerson: person[0].photo_url && person[0].photo_url,
            positionInCompany: person[0].position_in_company && person[0].position_in_company[0],
            description: person[0].description && person[0].description[0],
            socialLinks: {
                github: person[0].github && person[0].github[0],
                linkedin: person[0].linkedin && person[0].linkedin[0],
                xing: person[0].xing && person[0].xing[0],
                facebook: person[0].facebook && person[0].facebook[0]
            },
            isModalOpen: true
        } )
    };

    closePersonModal = () => {
        this.setState( {
            firstName: '',
            lastName: '',
            imageOfPerson: '',
            positionInCompany: '',
            description: '',
            socialLinks: {
                github: '',
                linkedin: '',
                xing: '',
                facebook: ''
            },
            isModalOpen: false
        } )
    };

    render() {
        const { selectedPersons } = this.props.wpObject;
        const { isModalOpen, closeOnEscape, closeOnDimmerClick } = this.state;
        const { socialLinks: { github, linkedin, xing, facebook } } = this.state;

        return (
            <Fragment>
                <Modal
                    open={ isModalOpen }
                    onClose={ () => this.closePersonModal() }
                    closeOnEscape={ closeOnEscape }
                    closeOnDimmerClick={ closeOnDimmerClick }
                >
                    <Modal.Header>Profile Details</Modal.Header>
                    <Modal.Content image>
                        <Image wrapped size='medium'
                               src={ this.state.imageOfPerson ? this.state.imageOfPerson : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png' }/>
                        <Modal.Description>
                            <Header><h1>{ `${ this.state.firstName } ${ this.state.lastName }` }</h1></Header>
                            <br/>
                            <Header>{ `${ this.state.positionInCompany }` }</Header>
                            <br/>
                            <p>
                                { this.state.description }
                            </p>
                            <br/>
                            <Grid columns={ 4 }>
                                <Grid.Row>
                                    { github !== '' ? (
                                        <Grid.Column>
                                            <a href={ this.checkValidUrl( github ) ? github : '#' }>
                                                <Icon link={ true } name="github"
                                                      size="huge"/>
                                            </a>
                                        </Grid.Column>
                                    ) : null }
                                    { linkedin !== '' ? (
                                        <Grid.Column>
                                            <a href={ this.checkValidUrl( linkedin ) ? linkedin : '#' }>
                                                <Icon link={ true } name="linkedin"
                                                      size="huge"/>
                                            </a>
                                        </Grid.Column>
                                    ) : null }
                                    { xing !== '' ? (
                                        <Grid.Column>
                                            <a href={ this.checkValidUrl( xing ) ? xing : '#' }>
                                                <Icon link={ true } name="xing"
                                                      size="huge"/>
                                            </a>
                                        </Grid.Column>
                                    ) : null }
                                    { facebook !== '' ? (
                                        <Grid.Column>
                                            <a href={ this.checkValidUrl( facebook ) ? facebook : '#' }>
                                                <Icon link={ true } name="facebook"
                                                      size="huge"/>
                                            </a>
                                        </Grid.Column>
                                    ) : null }
                                </Grid.Row>
                            </Grid>
                        </Modal.Description>
                    </Modal.Content>
                </Modal>
                <Grid columns={ 2 }>
                    {
                        selectedPersons && selectedPersons.length > 0 ? (
                                selectedPersons.map( ( selectedPerson ) => (
                                    <Grid.Column key={ selectedPerson.id }>
                                        <Card>
                                            <Card.Content>
                                                <Image
                                                    floated='right'
                                                    size='mini'
                                                    src={ (selectedPerson[0] && selectedPerson[0].photo_url)
                                                        ? selectedPerson[0].photo_url
                                                        : 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png' }
                                                />
                                                <Card.Header>{ `${ selectedPerson[0].first_name[0] } ${ selectedPerson[0].last_name[0] }` }</Card.Header>
                                                <Card.Meta>{ selectedPerson[0].position_in_company[0] }</Card.Meta>
                                            </Card.Content>
                                            <Card.Content extra>
                                                <div className='ui four buttons'>
                                                    <Button basic color='green'
                                                            onClick={ () => this.openPersonModal( selectedPerson ) }>
                                                        Show Details
                                                    </Button>
                                                </div>
                                            </Card.Content>
                                        </Card>
                                    </Grid.Column>
                                ) )
                            ) :
                            <Message>
                                <Message.Header>No Person Selected</Message.Header>
                                <p>
                                    No person has been selected. Please select some in order
                                    to show in front page.
                                </p>
                            </Message>
                    }
                </Grid>
            </Fragment>
        );
    }
}
