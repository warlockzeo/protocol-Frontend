import React from 'react';
import { Row, Col } from 'react-bootstrap';
import Styled from 'styled-components';
import moment from 'moment';

const P = Styled.p`
  margin-bottom: 0;
`;

const ProtocolList = ({ data, showProtocolNumber }) => {
  let list;
  if (data.length > 0) {
    list = data.map((protocolo, i) => {
      const {
        origem,
        origemNome,
        dep_origem,
        destino,
        destinoNome,
        dep_destino,
        portador,
        mat,
        copia,
        data,
        obs,
        doc,
        situacao,
        carater,
      } = protocolo;

      const protocolNumber = showProtocolNumber && protocolo.protocolo;
      const protocolNumberLine = showProtocolNumber ? (
        <Col md={1} style={{ fontWeight: 'bolder' }} className='text-center'>
          {protocolNumber}
        </Col>
      ) : (
        ''
      );
      const date = moment(data);

      if (copia === 'copia') {
        return (
          <Row key={i} className='return-busca'>
            {protocolNumberLine}
            <Col md={1} className='text-center'>
              {date.format('DD/MM/YYYY, h:mm:ss a')}
            </Col>
            <Col md={showProtocolNumber ? 10 : 11}>
              <P>
                <strong>De: </strong>
                {!!origemNome ? origemNome : origem} - {dep_origem}
                <br />
                <strong>Para: </strong>
                {!!destinoNome ? destinoNome : destino} -{dep_destino} - Por:
                {portador} /{mat}
                <br />
                <strong>{copia}</strong>
              </P>
            </Col>
          </Row>
        );
      } else {
        return (
          <Row key={i} className='return-busca-head'>
            {protocolNumberLine}
            <Col md={1} className='text-center'>
              {date.format('DD/MM/YYYY, h:mm:ss a')}
            </Col>
            <Col md={3}>
              <P>
                <strong>De:</strong>
                {!!origemNome ? origemNome : origem} - {dep_origem}
                <br />
                <strong>Para: </strong>
                {!!destinoNome ? destinoNome : destino} -{dep_destino}
                <br />
                <strong>Por: </strong> {portador} /{mat}
                <br />
                {copia && (
                  <span>
                    <strong>Cópias para:</strong> {copia}{' '}
                  </span>
                )}
              </P>
            </Col>
            <Col md={showProtocolNumber ? 4 : 5}>{obs}</Col>
            <Col md={1} className='text-center'>
              {doc}
            </Col>
            <Col md={1} className='text-center'>
              {situacao}
            </Col>
            <Col md={1} className='text-center'>
              {carater}
            </Col>
          </Row>
        );
      }
    });
  }

  return <div id='return-busca'>{list}</div>;
};

export default ProtocolList;
